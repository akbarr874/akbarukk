<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$sql = "SELECT t.*, b.nama AS nama_barang FROM transaksi t JOIN barang b ON t.barang_id = b.id ORDER BY t.tanggal DESC";
$res = $mysqli->query($sql);
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Transaksi</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    margin: 0;
    padding: 20px;
}

.container {
    background: #fff;
    padding: 25px;
    max-width: 800px;
    margin: auto;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h2 {
    margin-top: 0;
    font-size: 24px;
}

.btn-back {
    display: inline-block;
    background: #555;
    color: #fff;
    padding: 8px 14px;
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 15px;
}

.btn-back:hover {
    opacity: 0.8;
}

.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.styled-table th, .styled-table td {
    border: 1px solid #ccc;
    padding: 10px;
}

.styled-table th {
    background: #333;
    color: #fff;
}

.styled-table tr:nth-child(even) {
    background: #f9f9f9;
}

/* Warna jenis */
.pinjam {
    color: #d9534f;
    font-weight: bold;
}

.kembali {
    color: #5cb85c;
    font-weight: bold;
}

    </style>
</head>

<body>
    <div class="container">
        <h2>Daftar Transaksi</h2>
        <p><a href="index.php">Kembali</a></p>
        <table border="1" cellpadding="6" cellspacing="0">
            <tr>

                <th>Tanggal</th>
                <th>Barang</th>
                <th>Peminjam</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Catatan</th>
            </tr>
            <?php while ($row = $res->fetch_assoc()): ?>
                <tr>

                    <td><?= $row['tanggal'] ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($row['peminjam']) ?></td>
                    <td><?= $row['jenis'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= htmlspecialchars($row['catatan']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>