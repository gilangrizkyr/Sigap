<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>Kelola Akun Administrator<?= $this->endSection() ?>

<?= $this->section('page_subtitle') ?>Manajemen akun administrator, hak akses, unit penugasan, dan status keaktifan sistem Sigap.<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
    
    <!-- User List Section -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text-main);">Daftar Pengguna Admin</h3>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Administrator</th>
                        <th>Email</th>
                        <th>Role / Otoritas</th>
                        <th>Cakupan Akses</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($users as $user): ?>
                        <tr>
                            <td><strong><?= $i++ ?></strong></td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-main);"><?= esc($user['name']) ?></div>
                            </td>
                            <td><?= esc($user['email']) ?></td>
                            <td>
                                <?php 
                                    $badgeClass = 'submitted';
                                    if ($user['role'] === 'superadmin') {
                                        $badgeClass = 'resolved';
                                    } elseif ($user['role'] === 'admin_dpmptsp') {
                                        $badgeClass = 'processing';
                                    } elseif ($user['role'] === 'admin_mpp') {
                                        $badgeClass = 'verified';
                                    } elseif ($user['role'] === 'pic_unit') {
                                        $badgeClass = 'waiting_response';
                                    }
                                    
                                    $roleMapping = [
                                        'superadmin'    => 'Super Admin',
                                        'admin_dpmptsp' => 'Admin DPMPTSP',
                                        'admin_mpp'     => 'Admin MPP',
                                        'pic_unit'      => 'PIC Unit Layanan'
                                    ];
                                ?>
                                <span class="badge badge-<?= $badgeClass ?>"><?= $roleMapping[$user['role']] ?? esc($user['role']) ?></span>
                            </td>
                            <td>
                                <?php if ($user['role'] === 'superadmin'): ?>
                                    <span style="font-size: 13px; color: var(--success); font-weight: 600;"><i class="fa-solid fa-earth-asia"></i> Akses Penuh</span>
                                <?php elseif ($user['role'] === 'admin_dpmptsp'): ?>
                                    <span style="font-size: 13px; color: #3b82f6;"><i class="fa-solid fa-building"></i> DPMPTSP</span>
                                <?php elseif ($user['role'] === 'admin_mpp'): ?>
                                    <span style="font-size: 13px; color: #10b981;"><i class="fa-solid fa-building-flag"></i> MPP</span>
                                <?php elseif ($user['role'] === 'pic_unit'): ?>
                                    <span style="font-size: 12px; color: var(--text-muted);"><i class="fa-solid fa-briefcase"></i> <?= esc($user['service_unit_name'] ?: 'Belum ditugaskan') ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ((int)$user['is_active'] === 1): ?>
                                    <span class="badge badge-resolved" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-rejected" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px;">
                                    <!-- Toggle Status Form -->
                                    <form action="<?= base_url('admin/users/toggle/' . $user['id']) ?>" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status keaktifan admin ini?')">
                                        <button type="submit" class="btn <?= ((int)$user['is_active'] === 1) ? 'btn-warning' : 'btn-success' ?>" style="padding: 6px 10px; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px;" title="<?= ((int)$user['is_active'] === 1) ? 'Nonaktifkan' : 'Aktifkan' ?>" <?= ((int)$user['id'] === (int)session()->get('user_id')) ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' ?>>
                                            <i class="fa-solid <?= ((int)$user['is_active'] === 1) ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                                        </button>
                                    </form>

                                    <!-- Reset Password Button (Edit) -->
                                    <button onclick="resetPasswordPrompt(<?= $user['id'] ?>, '<?= esc($user['name']) ?>')" class="btn btn-info" style="padding: 6px 10px; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #0ea5e9; border: 1px solid #0ea5e9;" title="Reset Password / Edit">
                                        <i class="fa-solid fa-key"></i>
                                    </button>

                                    <!-- Delete Form -->
                                    <form action="<?= base_url('admin/users/delete/' . $user['id']) ?>" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin ini secara permanen?')">
                                        <button type="submit" class="btn btn-danger" style="padding: 6px 10px; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #ef4444; border: 1px solid #ef4444;" title="Hapus Akun" <?= ((int)$user['id'] === (int)session()->get('user_id')) ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' ?>>
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create User Form Section -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text-main);">Tambah Admin Baru</h3>
        
        <form action="<?= base_url('admin/users/create') ?>" method="POST">
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-input" placeholder="Masukkan nama admin..." required>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Alamat Email</label>
                <input type="email" name="email" class="form-input" placeholder="Masukkan email..." required>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Password Sementara</label>
                <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter..." required minlength="6">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Tingkat Hak Akses (Role)</label>
                <select name="role" id="roleSelector" class="form-input" required onchange="handleRoleChange(this.value)">
                    <option value="superadmin">Super Admin (Akses Global)</option>
                    <option value="admin_dpmptsp">Admin DPMPTSP</option>
                    <option value="admin_mpp">Admin MPP</option>
                    <option value="pic_unit">PIC Unit Layanan (Scope Layanan Tertentu)</option>
                </select>
            </div>

            <!-- Service Unit dropdown (Only shown when PIC Unit role is selected) -->
            <div class="form-group" id="serviceUnitGroup" style="margin-bottom: 16px; display: none;">
                <label class="form-label">Service Unit Penugasan</label>
                <select name="service_unit_id" class="form-input">
                    <option value="">-- Pilih Service Unit --</option>
                    <?php foreach ($service_units as $unit): ?>
                        <option value="<?= $unit['id'] ?>"><?= esc($unit['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small style="color: var(--text-muted); font-size: 11px;">Lokasi fisik (DPMPTSP/MPP) akan terdeteksi otomatis dari unit ini.</small>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 12px; padding: 12px;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Akun Admin
            </button>
        </form>
    </div>

</div>

<!-- Hidden reset password form triggered by JavaScript -->
<form id="resetPasswordForm" action="" method="POST" style="display:none;">
    <input type="password" name="password" id="resetPasswordInput">
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function handleRoleChange(role) {
        const serviceUnitGroup = document.getElementById('serviceUnitGroup');
        if (role === 'pic_unit') {
            serviceUnitGroup.style.display = 'block';
            serviceUnitGroup.querySelector('select').setAttribute('required', 'required');
        } else {
            serviceUnitGroup.style.display = 'none';
            serviceUnitGroup.querySelector('select').removeAttribute('required');
        }
    }

    function resetPasswordPrompt(userId, userName) {
        const newPassword = prompt("Masukkan password baru untuk " + userName + ":");
        if (newPassword === null) return; // cancelled
        if (newPassword.trim().length < 6) {
            alert("Password minimal harus 6 karakter.");
            return;
        }

        const form = document.getElementById('resetPasswordForm');
        form.action = "<?= base_url('admin/users/reset-password/') ?>/" + userId;
        document.getElementById('resetPasswordInput').value = newPassword;
        form.submit();
    }
</script>
<?= $this->endSection() ?>
