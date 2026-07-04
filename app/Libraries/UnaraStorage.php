<?php

namespace App\Libraries;

/**
 * ============================================================================
 * UnaraStorage — PHP Client Library
 * ============================================================================
 *
 * A simple, dependency-free PHP client for communicating with the
 * Unara Storage API. Uses cURL internally — no third-party packages needed.
 *
 * ── What this client can do ─────────────────────────────────────────────────
 *   upload()    Upload a file into a bucket
 *   update()    Replace an existing file (keeps same URL)
 *   download()  Download a file and optionally save it locally
 *   delete()    Permanently delete an object from a bucket
 *   list()      List all objects in a bucket
 *   url()       Get the direct public URL of an object (no HTTP request)
 *
 * ── What this client cannot do ──────────────────────────────────────────────
 *   - Create or manage applications  (admin panel only)
 *   - Create or manage buckets       (admin panel only)
 *
 * ── Authentication ──────────────────────────────────────────────────────────
 * All API requests are authenticated via the X-API-Key HTTP header.
 * The key is obtained from the Unara admin panel and passed to the constructor.
 *
 * ── Error handling ──────────────────────────────────────────────────────────
 * Every method returns an associative array containing at minimum:
 *   ['status'  => 'success' | 'error']
 *   ['message' => 'Human readable description']
 *
 * On success, additional keys are returned depending on the operation.
 * Always check ['status'] before accessing other keys.
 *
 * ── Basic usage ─────────────────────────────────────────────────────────────
 *   $client = new UnaraStorage('https://your-unara-server.com', 'your-api-key');
 *
 *   $result = $client->upload('my-bucket', $file);         // upload
 *   $url    = $client->url('my-bucket', $objectName);      // get URL
 *   $result = $client->delete('my-bucket', $objectName);   // delete
 */
class UnaraStorage
{
    /**
     * Base URL of the Unara Storage server.
     * Trailing slash is stripped in the constructor.
     */
    private string $baseUrl;

    /**
     * API key from the Unara admin panel.
     * Sent as X-API-Key header on every authenticated request.
     */
    private string $apiKey;

    /**
     * cURL request timeout in seconds.
     */
    private int $timeout;

    /**
     * @param string $baseUrl  Base URL of your Unara Storage server (no trailing slash)
     * @param string $apiKey   API key from the Unara admin panel
     * @param int    $timeout  cURL request timeout in seconds (default: 30)
     */
    public function __construct(string $baseUrl, string $apiKey, int $timeout = 30)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey  = $apiKey;
        $this->timeout = $timeout;
    }

    // =========================================================================
    // UPLOAD
    // =========================================================================

    /**
     * Upload a file to a bucket.
     *
     * Accepts either a CI4 UploadedFile object (from $request->getFile())
     * or a plain local file path string. Both are handled transparently.
     *
     * Logic:
     *   1. Detect if input is a CI4 UploadedFile or a plain path string
     *   2. Extract file path, original name, and mime type accordingly
     *   3. Build multipart/form-data POST body with bucket_name + file
     *   4. Send to POST /api/upload with X-API-Key header
     *   5. Return server response with object metadata
     *
     * @param  string $bucketName  Target bucket name (must exist in admin panel)
     * @param  mixed  $file        CI4 UploadedFile object OR absolute local file path string
     * @return array               ['status', 'message', 'object' => [...]]
     */
    public function upload(string $bucketName, mixed $file): array
    {
        // Step 1 & 2 — Detect input type and extract file info
        if ($file instanceof \CodeIgniter\HTTP\Files\UploadedFile) {
            // CI4 UploadedFile — validate before extracting info
            if (!$file->isValid() || $file->hasMoved()) {
                return ['status' => 'error', 'message' => 'Invalid or already moved file'];
            }
            $filePath     = $file->getTempName();       // temp path PHP uploaded to
            $originalName = $file->getClientName();     // original filename from browser
            $mimeType     = $file->getClientMimeType(); // mime type reported by browser
        } else {
            // Plain file path string
            if (!file_exists($file)) {
                return ['status' => 'error', 'message' => "File not found: {$file}"];
            }
            $filePath     = $file;
            $originalName = basename($file);
            $mimeType     = mime_content_type($file);
        }

        $tempWebp = null;
        $convertedWebp = $this->convertToWebp($filePath, $mimeType);
        if ($convertedWebp !== null) {
            $filePath     = $convertedWebp;
            $tempWebp     = $convertedWebp;
            $mimeType     = 'image/webp';
            $originalName = pathinfo($originalName, PATHINFO_FILENAME) . '.webp';
        }

        // Step 3 — Build multipart POST body
        $postData = [
            'bucket_name' => $bucketName,
            'file'        => new \CURLFile($filePath, $mimeType, $originalName),
        ];

        // Step 4 & 5 — Send request and return response
        $result = $this->request('POST', '/api/upload', [], $postData);

        if ($tempWebp !== null && file_exists($tempWebp)) {
            unlink($tempWebp);
        }

        return $result;
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    /**
     * Replace an existing object with a new file.
     *
     * The object_name and its URL remain unchanged after the update.
     * Only the file contents, mime_type, file_size, and e_tag are replaced.
     * This means any <img src="">, database references, or external links
     * pointing to the object_name automatically serve the new file.
     *
     * Accepts either a CI4 UploadedFile object or a plain local file path string.
     *
     * Logic:
     *   1. Detect if input is a CI4 UploadedFile or a plain path string
     *   2. Extract file path, original name, and mime type accordingly
     *   3. Build multipart/form-data POST body with new file
     *   4. Send to POST /api/update/{bucket}/{object} with X-API-Key header
     *   5. Return server response with updated object metadata
     *
     * @param  string $bucketName  Name of the bucket
     * @param  string $objectName  object_name of the file to replace (UUID filename from DB)
     * @param  mixed  $file        CI4 UploadedFile object OR absolute local file path string
     * @return array               ['status', 'message', 'object' => [...]]
     */
    public function update(string $bucketName, string $objectName, mixed $file): array
    {
        // Step 1 & 2 — Detect input type and extract file info
        if ($file instanceof \CodeIgniter\HTTP\Files\UploadedFile) {
            // CI4 UploadedFile — validate before extracting info
            if (!$file->isValid() || $file->hasMoved()) {
                return ['status' => 'error', 'message' => 'Invalid or already moved file'];
            }
            $filePath     = $file->getTempName();
            $originalName = $file->getClientName();
            $mimeType     = $file->getClientMimeType();
        } else {
            // Plain file path string
            if (!file_exists($file)) {
                return ['status' => 'error', 'message' => "File not found: {$file}"];
            }
            $filePath     = $file;
            $originalName = basename($file);
            $mimeType     = mime_content_type($file);
        }

        $tempWebp = null;
        $convertedWebp = $this->convertToWebp($filePath, $mimeType);
        if ($convertedWebp !== null) {
            $filePath     = $convertedWebp;
            $tempWebp     = $convertedWebp;
            $mimeType     = 'image/webp';
            $originalName = pathinfo($originalName, PATHINFO_FILENAME) . '.webp';
        }

        // Step 3 — Build multipart POST body with new file only
        $postData = [
            'file' => new \CURLFile($filePath, $mimeType, $originalName),
        ];

        // Step 4 & 5 — Send request and return response
        $result = $this->request('POST', "/api/update/{$bucketName}/{$objectName}", [], $postData);

        if ($tempWebp !== null && file_exists($tempWebp)) {
            unlink($tempWebp);
        }

        return $result;
    }

    // =========================================================================
    // DOWNLOAD
    // =========================================================================

    /**
     * Download an object from a bucket and optionally save it locally.
     *
     * Logic:
     *   1. Send GET /api/download/{bucket}/{object} with X-API-Key header
     *   2. If response is a JSON error, return the error array
     *   3. If savePath is given, write raw bytes to that local path
     *   4. If no savePath, return raw file content in the response array
     *
     * @param  string      $bucketName  Name of the bucket
     * @param  string      $objectName  object_name stored in DB (UUID filename)
     * @param  string|null $savePath    Local path to save the file
     *                                  If null, raw content is returned in ['content']
     * @return array                    ['status', 'message', 'saved_to'] or ['status', 'content']
     */
    public function download(string $bucketName, string $objectName, ?string $savePath = null): array
    {
        // Step 1 — Send authenticated download request (raw response needed)
        $response = $this->request('GET', "/api/download/{$bucketName}/{$objectName}", [], null, true);

        // Step 2 — If server returned a JSON error, pass it through
        if (is_array($response) && isset($response['status']) && $response['status'] === 'error') {
            return $response;
        }

        // Step 3 — Save to local path if provided
        if ($savePath !== null) {
            $dir = dirname($savePath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            if (file_put_contents($savePath, $response) === false) {
                return [
                    'status'  => 'error',
                    'message' => "Failed to write file to: {$savePath}"
                ];
            }

            return [
                'status'   => 'success',
                'message'  => 'File downloaded successfully',
                'saved_to' => $savePath,
            ];
        }

        // Step 4 — No save path — return raw bytes in response
        return [
            'status'  => 'success',
            'message' => 'File content retrieved',
            'content' => $response, // raw binary string
        ];
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    /**
     * Permanently delete an object from a bucket.
     *
     * Deletes both the physical file on disk and the database record.
     * This action is irreversible.
     *
     * Logic:
     *   1. Send DELETE /api/delete/{bucket}/{object} with X-API-Key header
     *   2. Server deletes physical file then database record
     *   3. Return server response
     *
     * @param  string $bucketName  Name of the bucket
     * @param  string $objectName  object_name stored in DB (UUID filename)
     * @return array               ['status', 'message']
     */
    public function delete(string $bucketName, string $objectName): array
    {
        return $this->request('DELETE', "/api/delete/{$bucketName}/{$objectName}");
    }

    // =========================================================================
    // LIST
    // =========================================================================

    /**
     * List all objects inside a bucket.
     *
     * Returns full metadata for every object including a public serve URL
     * appended to each entry for convenience.
     *
     * Logic:
     *   1. Send GET /api/list/{bucket} with X-API-Key header
     *   2. Return array of object metadata rows
     *
     * @param  string $bucketName  Name of the bucket
     * @return array               ['status', 'total', 'objects' => [...]]
     */
    public function list(string $bucketName): array
    {
        return $this->request('GET', "/api/list/{$bucketName}");
    }

    // =========================================================================
    // URL
    // =========================================================================

    /**
     * Get the direct public URL of an object. No HTTP request is made.
     *
     * The returned URL points to the /api/serve/ endpoint which:
     *   - Requires NO authentication
     *   - Can be used directly in <img src="">, <a href="">, <video src="">, etc.
     *   - Only works for PUBLIC buckets (private buckets return 403)
     *   - Implements HTTP ETag caching for performance
     *
     * For private bucket access, use download() which sends the API key
     * and returns the raw file content for you to proxy yourself.
     *
     * Logic:
     *   Simply constructs and returns the serve URL string.
     *   No network call is made.
     *
     * @param  string $bucketName  Name of the bucket
     * @param  string $objectName  object_name stored in DB (UUID filename)
     * @return string              Direct public URL e.g. https://server.com/api/serve/bucket/object
     */
    public function url(string $bucketName, string $objectName): string
    {
        return "{$this->baseUrl}/api/serve/{$bucketName}/{$objectName}";
    }

    // =========================================================================
    // PRIVATE — HTTP REQUEST HELPER
    // =========================================================================

    /**
     * Execute an HTTP request using cURL.
     *
     * Handles all cURL setup, header injection, method routing,
     * error handling, and response decoding in one place.
     *
     * Logic:
     *   1. Build full URL from baseUrl + endpoint
     *   2. Inject X-API-Key header into every request
     *   3. Configure cURL for the given HTTP method (GET / POST / DELETE)
     *   4. Execute the request
     *   5. Handle cURL-level failures (network errors, DNS failures, etc.)
     *   6. If rawResponse is true, return raw bytes (for file downloads)
     *      - But if server returned a JSON error body, decode and return that
     *   7. Decode JSON response body and return as array
     *   8. If JSON decode fails, return error array with raw body for debugging
     *
     * @param  string     $method       HTTP method: GET | POST | DELETE
     * @param  string     $endpoint     API path e.g. /api/upload
     * @param  array      $headers      Additional headers to merge with defaults
     * @param  array|null $postData     POST body (multipart array for file uploads)
     * @param  bool       $rawResponse  If true, return raw response bytes instead of JSON
     * @return array|string             Decoded JSON array, or raw bytes if $rawResponse = true
     */
    private function request(
        string $method,
        string $endpoint,
        array $headers = [],
        ?array $postData = null,
        bool $rawResponse = false
    ): array|string {

        // Step 1 — Build full URL
        $url = $this->baseUrl . $endpoint;

        // Step 2 — Default headers always include the API key
        $defaultHeaders = [
            'X-API-Key: ' . $this->apiKey,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,         // return response as string
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_HTTPHEADER     => array_merge($defaultHeaders, $headers),
            CURLOPT_SSL_VERIFYPEER => true,         // verify SSL certificate
        ]);

        // Step 3 — Configure method-specific cURL options
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if ($postData !== null) {
                    // Passing an array with CURLFile triggers multipart/form-data automatically
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                }
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;

            case 'GET':
            default:
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
        }

        // Step 4 — Execute request
        $responseBody = curl_exec($ch);
        $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError    = curl_error($ch);
        curl_close($ch);

        // Step 5 — Handle cURL-level failures (network, DNS, timeout, etc.)
        if ($responseBody === false) {
            return [
                'status'  => 'error',
                'message' => 'cURL error: ' . $curlError
            ];
        }

        // Step 6 — Raw response mode (for file downloads)
        if ($rawResponse) {
            // If server sent back a JSON error (e.g. 404, 403), decode and return it
            $decoded = json_decode($responseBody, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded['status'])) {
                return $decoded;
            }
            // Otherwise return raw bytes as-is
            return $responseBody;
        }

        // Step 7 — Decode JSON response
        $decoded = json_decode($responseBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Step 8 — JSON decode failed — return raw body for debugging
            return [
                'status'    => 'error',
                'message'   => 'Invalid JSON response from server',
                'http_code' => $httpCode,
                'raw'       => $responseBody,
            ];
        }

        return $decoded;
    }

    /**
     * Convert an image to WebP format if GD library is available.
     */
    private function convertToWebp(string $sourcePath, string $mimeType): ?string
    {
        if (!function_exists('imagecreatefromstring') || !function_exists('imagewebp')) {
            return null; // GD or WebP support not available, skip
        }

        // Only convert standard web-friendly images
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
        if (!in_array(strtolower($mimeType), $allowedMimes)) {
            return null;
        }

        $imageRaw = file_get_contents($sourcePath);
        if ($imageRaw === false) {
            return null;
        }

        $image = imagecreatefromstring($imageRaw);
        if ($image === false) {
            return null;
        }

        $tempWebpPath = tempnam(sys_get_temp_dir(), 'webp_');
        if ($tempWebpPath === false) {
            imagedestroy($image);
            return null;
        }

        // Handle transparency for PNG/WebP
        imagealphablending($image, false);
        imagesavealpha($image, true);

        // Convert and save
        if (imagewebp($image, $tempWebpPath, 80)) {
            imagedestroy($image);
            return $tempWebpPath;
        }

        imagedestroy($image);
        return null;
    }
}
