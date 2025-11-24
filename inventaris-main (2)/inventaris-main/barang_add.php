<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $jumlah = (int)$_POST['jumlah'];
    $lokasi = trim($_POST['lokasi']);
    $kode = trim($_POST['kode']);

    if ($nama === '' || $jumlah < 0) $error = 'Nama dan jumlah harus diisi dengan benar.';
    else {
        $tersedia = $jumlah;
        $stmt = $mysqli->prepare("INSERT INTO barang (nama, deskripsi, jumlah, tersedia, lokasi, kode) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssiiss', $nama, $deskripsi, $jumlah, $tersedia, $lokasi, $kode);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $error = "Gagal menyimpan: " . $mysqli->error;
        }
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tambah Barang</title>
    <link rel="stylesheet">
    <style>
        /* ===== TITLE ===== */
.container h2 {
    text-align: center;
    color: #0A2A42;
    margin-bottom: 20px;
    font-weight: 700;
}

/* ===== FORM LABEL ===== */
label {
    font-size: 14px;
    font-weight: 600;
    color: #0A2A42;
}

/* ===== INPUT & TEXTAREA ===== */
input[type="text"],
input[type="number"],
textarea {
    width: 100%;
    padding: 12px;
    margin-top: 6px;
    margin-bottom: 18px;
    border: 1px solid #BFCAD3;
    border-radius: 10px;
    background: #F4F4F4;
    font-size: 14px;
    transition: 0.25s ease;
    resize: vertical;
}

textarea {
    min-height: 100px;
}

input:focus,
textarea:focus {
    background: #ffffff;
    border-color: #145374;
    box-shadow: 0 0 6px rgba(20,83,116,0.3);
    outline: none;
}

/* ===== BUTTONS ===== */
button {
    width: 100%;
    padding: 12px;
    background-color: #145374;
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: 0.25s ease;
    margin-bottom: 10px;
}

button:hover {
    background-color: #0A2A42;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

/* Tombol Kembali alternatif */
button.secondary {
    background-color: #777;
}

button.secondary:hover {
    background-color: #555;
}

/* ===== ERROR MESSAGE ===== */
.error {
    background-color: #ffdddd;
    color: #cc0000;
    padding: 10px;
    border-left: 4px solid #cc0000;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
}

    </style>
</head>

<body>
    <div class="container">
        <h2>Tambah Barang</h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Nama Barang</label><br>
            <input type="text" name="nama" required><br>
            <label>Kode (unik)</label><br>
            <input type="text" name="kode"><br>
            <label>Deskripsi</label><br>
            <textarea name="deskripsi"></textarea><br>
            <label>Jumlah</label><br>
            <input type="number" name="jumlah" value="1" min="0" required><br>
            <label>Lokasi</label><br>
            <input type="text" name="lokasi"><br><br>
            <button type="submit">Simpan</button>
            <button type="submit" onclick="window.location.href='index.php'">Kembali</button>
        </form>

    </div>
</body>

</html>