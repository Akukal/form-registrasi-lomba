<?php
$warning = '';
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi Nama
    $nama = trim($_POST['nama'] ?? '');
    if (strlen($nama) < 3 || strlen($nama) > 50) {
        $errors['nama'] = 'Nama harus 3-50 karakter';
    }
    
    // Validasi Email
    $email = trim($_POST['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email tidak valid';
    }
    
    // Validasi Nomor HP
    $no_hp = trim($_POST['no_hp'] ?? '');
    if (!preg_match('/^(\+?62|0)[8-9][0-9]{6,10}$/', $no_hp)) {
        $errors['no_hp'] = 'Format: 08xx atau 09xx (8-12 digit)';
    }
    
    // Validasi Umur
    $umur = (int)($_POST['umur'] ?? 0);
    if ($umur < 17 || $umur > 19) {
        $warning = 'Umur harus 17-19 tahun. Pendaftaran tidak dapat disimpan.';
        $errors['umur'] = 'Umur harus 17-19 tahun';
    }
    
    // Validasi GitHub URL (opsional)
    $github_url = trim($_POST['github_url'] ?? '');
    if ($github_url && !preg_match('/^https:\/\/github\.com\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+$/', $github_url)) {
        $errors['github_url'] = 'URL GitHub tidak valid';
    }
    
    // Validasi Kategori (whitelist)
    $kategori_valid = ['web', 'mobile', 'desktop', 'data', 'security'];
    $kategori = $_POST['kategori'] ?? '';
    if (!in_array($kategori, $kategori_valid, true)) {
        $errors['kategori'] = 'Pilih kategori yang valid';
    }
    
    // Validasi Peserta (whitelist)
    $peserta_valid = ['individu', 'tim'];
    $peserta = $_POST['peserta'] ?? '';
    if (!in_array($peserta, $peserta_valid, true)) {
        $errors['peserta'] = 'Pilih jenis peserta';
    }
    
    // Simpan jika tidak ada error
    if (empty($errors)) {
        // Proses penyimpanan (database, file, dll)
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Lomba Coding</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { margin-bottom: 20px; color: #333; }
        .warning { background: #fff3cd; color: #856404; padding: 12px; border-radius: 4px; margin-bottom: 15px; }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; }
        .field { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        .required { color: red; }
        input[type="text"], input[type="email"], input[type="number"], input[type="url"], select {
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;
        }
        input:focus, select:focus { border-color: #007bff; outline: none; }
        .error { color: #dc3545; font-size: 13px; margin-top: 3px; }
        .radio-group { display: flex; gap: 15px; }
        .radio-group label { font-weight: normal; display: flex; align-items: center; }
        .radio-group input[type="radio"] { width: auto; margin-right: 5px; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; 
                 font-size: 16px; cursor: pointer; margin-top: 10px; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registrasi Lomba Coding</h1>
        
        <?php if ($warning): ?>
            <div class="warning"><?= htmlspecialchars($warning) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success">✓ Pendaftaran berhasil disimpan!</div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="field">
                <label>Nama <span class="required">*</span></label>
                <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                <?php if (isset($errors['nama'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['nama']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="field">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="field">
                <label>Nomor HP <span class="required">*</span></label>
                <input type="text" name="no_hp" value="<?= htmlspecialchars($_POST['no_hp'] ?? '') ?>" required 
                       placeholder="08xxxxxxxxxx">
                <?php if (isset($errors['no_hp'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['no_hp']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="field">
                <label>Umur (17-19 tahun) <span class="required">*</span></label>
                <input type="number" name="umur" value="<?= htmlspecialchars($_POST['umur'] ?? '') ?>" 
                       min="17" max="19" required>
                <?php if (isset($errors['umur'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['umur']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="field">
                <label>URL Repositori GitHub (opsional)</label>
                <input type="url" name="github_url" value="<?= htmlspecialchars($_POST['github_url'] ?? '') ?>" 
                       placeholder="https://github.com/username/repo">
                <?php if (isset($errors['github_url'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['github_url']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="field">
                <label>Kategori <span class="required">*</span></label>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="web" <?= ($_POST['kategori'] ?? '') === 'web' ? 'selected' : '' ?>>Web Development</option>
                    <option value="mobile" <?= ($_POST['kategori'] ?? '') === 'mobile' ? 'selected' : '' ?>>Mobile Development</option>
                    <option value="desktop" <?= ($_POST['kategori'] ?? '') === 'desktop' ? 'selected' : '' ?>>Desktop Development</option>
                    <option value="data" <?= ($_POST['kategori'] ?? '') === 'data' ? 'selected' : '' ?>>Data Science & AI</option>
                    <option value="security" <?= ($_POST['kategori'] ?? '') === 'security' ? 'selected' : '' ?>>Cyber Security</option>
                </select>
                <?php if (isset($errors['kategori'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['kategori']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="field">
                <label>Peserta <span class="required">*</span></label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="peserta" value="individu" 
                               <?= ($_POST['peserta'] ?? '') === 'individu' ? 'checked' : '' ?> required>
                        Individu
                    </label>
                    <label>
                        <input type="radio" name="peserta" value="tim" 
                               <?= ($_POST['peserta'] ?? '') === 'tim' ? 'checked' : '' ?>>
                        Tim (max 3 orang)
                    </label>
                </div>
                <?php if (isset($errors['peserta'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['peserta']) ?></div>
                <?php endif; ?>
            </div>
            
            <button type="submit">Daftar Sekarang</button>
        </form>
    </div>
</body>
</html>
