<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeliharaan Sistem — Sigap</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.15);
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 20px;
        }

        .container {
            max-width: 550px;
            width: 100%;
            text-align: center;
            position: relative;
        }

        /* Abstract glowing background circles */
        .glow-circle {
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--primary);
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            z-index: 1;
        }
        .glow-circle-1 { top: -150px; left: -150px; }
        .glow-circle-2 { bottom: -150px; right: -150px; }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 48px 32px;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 2;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icon-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 96px;
            height: 96px;
            background: var(--primary-glow);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 50%;
            margin-bottom: 28px;
            color: var(--primary);
            position: relative;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
            70% { box-shadow: 0 0 0 16px rgba(99, 102, 241, 0); }
            100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
        }

        .icon-container svg {
            width: 48px;
            height: 48px;
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 16px;
            background: linear-gradient(to right, #ffffff, #c7d2fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        p {
            font-size: 1.05rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .badge {
            display: inline-block;
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            padding: 8px 16px;
            border-radius: 99px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 24px;
        }

        .footer {
            margin-top: 32px;
            font-size: 0.85rem;
            color: rgba(148, 163, 184, 0.6);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="glow-circle glow-circle-1"></div>
        <div class="glow-circle glow-circle-2"></div>
        
        <div class="card">
            <div class="badge">PEMELIHARAAN SISTEM</div>
            <div class="icon-container">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A1.79 1.79 0 1114.75 23.5l-5.83-5.83M11.42 15.17l2.42-2.42M11.42 15.17L6 10.5m0 0l-5.83-5.83A1.79 1.79 0 112.67 2.17L8.5 8m-2.5 2.5l5.42-5.42M17.25 3.75a1.79 1.79 0 112.5 2.5l-5.83 5.83m0 0l-2.42 2.42m2.42-2.42L19.5 18"></path>
                </svg>
            </div>
            <h1>Sigap</h1>
            <p>Saat ini kami sedang melakukan pembaruan berkala pada sistem pengaduan publik untuk meningkatkan kecepatan dan keandalan layanan. Portal akan segera kembali dalam beberapa saat.</p>
            
            <div class="footer">
                &copy; 2026 Sigap. Hak Cipta Dilindungi.
            </div>
        </div>
    </div>
</body>
</html>
