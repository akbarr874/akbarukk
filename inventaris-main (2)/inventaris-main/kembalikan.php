<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) die('Barang tidak ditemukan.');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $peminjam = trim($_POST['peminjam']);
    $jumlah = (int)$_POST['jumlah'];
    $catatan = trim($_POST['catatan']);

    if ($peminjam === '' || $jumlah <= 0) $error = 'Isi peminjam dan jumlah dengan benar.';
    else {
        // insert transaksi kembali
        $stmt = $mysqli->prepare("INSERT INTO transaksi (barang_id, peminjam, jenis, jumlah, catatan) VALUES (?, ?, 'kembali', ?, ?)");
        $stmt->bind_param('isis', $id, $peminjam, $jumlah, $catatan);
        if ($stmt->execute()) {
            // tambahkan tersedia (tidak boleh melebihi jumlah total)
            $stmt2 = $mysqli->prepare("UPDATE barang SET tersedia = LEAST(jumlah, tersedia + ?) WHERE id = ?");
            $stmt2->bind_param('ii', $jumlah, $id);
            $stmt2->execute();
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
    <title>Kembalikan Barang</title>
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

/* ===== BUTTON ===== */
button {
    width: 100%;
    padding: 12px;
    background-color: #145374;
    border: none;
    border-radius: 10px;
    color: #ffffff;
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

/* ===== BACK LINK ===== */
.container a {
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
        <h2>Kembalikan: <?= htmlspecialchars($data['nama']) ?></h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Nama Pengembali (atau nama peminjam)</label><br>
            <input type="text" name="peminjam" required><br>
            <label>Jumlah yang Dikembalikan</label><br>
            <input type="number" name="jumlah" value="1" min="1" required><br>
            <label>Catatan</label><br>
            <textarea name="catatan"></textarea><br><br>
            <button type="submit">Kembalikan</button>
        </form>
        <p><a href="index.php">Kembali</a></p>
    </div>
</body>

</html>