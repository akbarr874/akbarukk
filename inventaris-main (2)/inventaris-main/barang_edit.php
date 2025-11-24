<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$data = $res->fetch_assoc()) {
    die("Barang tidak ditemukan.");
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $jumlah = (int)$_POST['jumlah'];
    $lokasi = trim($_POST['lokasi']);
    $kode = trim($_POST['kode']);

    // adjust 'tersedia' bila total jumlah berubah
    $selisih = $jumlah - $data['jumlah'];
    $tersedia = $data['tersedia'] + $selisih;
    if ($tersedia < 0) $tersedia = 0;

    $stmt = $mysqli->prepare("UPDATE barang SET nama=?, deskripsi=?, jumlah=?, tersedia=?, lokasi=?, kode=? WHERE id=?");
    $stmt->bind_param('ssiissi', $nama, $deskripsi, $jumlah, $tersedia, $lokasi, $kode, $id);
    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Gagal update: " . $mysqli->error;
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Edit Barang</title>
    <style>
        /* ===== FORM CONTAINER ===== */
.container h2 {
    text-align: center;
    color: #0A2A42;
    margin-bottom: 22px;
    font-weight: 700;
}

/* ===== LABEL ===== */
label {
    color: #0A2A42;
    font-size: 14px;
    font-weight: 600;
}

/* ===== INPUT & TEXTAREA ===== */
input[type="text"],
input[type="number"],
textarea {
    width: 100%;
    padding: 12px;
    margin-top: 6px;
    margin-bottom: 18px;
    border-radius: 10px;
    border: 1px solid #BFCAD3;
    background-color: #F4F4F4;
    transition: 0.25s ease;
    font-size: 14px;
    resize: vertical;
}

textarea {
    min-height: 110px;
}

input:focus,
textarea:focus {
    background-color: #ffffff;
    border-color: #145374;
    box-shadow: 0 0 6px rgba(20,83,116,0.3);
    outline: none;
}

/* ===== BUTTON ===== */
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
}

button:hover {
    background-color: #0A2A42;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

/* ===== ERROR MESSAGE ===== */
.error {
    background-color: #ffdddd;
    color: #cc0000;
    padding: 10px;
    border-left: 4px solid #cc0000;
    margin-bottom: 15px;
    border-radius: 6px;
    font-size: 14px;
}

/* ===== LINK BACK ===== */
.container a {
    display: inline-block;
    margin-top: 10px;
    color: #145374;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s ease;
}

.container a:hover {
    color: #0A2A42;
    text-decoration: underline;
}

    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Barang</h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Nama</label><br>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required><br>
            <label>Kode</label><br>
            <input type="text" name="kode" value="<?= htmlspecialchars($data['kode']) ?>"><br>
            <label>Deskripsi</label><br>
            <textarea name="deskripsi"><?= htmlspecialchars($data['deskripsi']) ?></textarea><br>
            <label>Jumlah (total)</label><br>
            <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" min="0" required><br>
            <label>Lokasi</label><br>
            <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>"><br><br>
            <button type="submit">Update</button>
        </form>
        <p><a href="index.php">Kembali</a></p>
    </div>
</body>

</html>