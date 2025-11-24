<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

// ambil list barang
$result = $mysqli->query("SELECT * FROM barang ORDER BY created_at DESC");
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Inventaris Barang</title>
    <link rel="stylesheet">
    <style>
        /* ===== GLOBAL CONTAINER ===== */
.container {
    max-width: 1000px;
    margin: 40px auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

/* ===== TITLE ===== */
h1 {
    text-align: center;
    color: #0A2A42;
    margin-bottom: 20px;
    font-weight: 700;
}

/* ===== WELCOME TEXT ===== */
.container p {
    font-size: 15px;
    color: #0A2A42;
    margin-bottom: 15px;
    line-height: 1.5;
}

/* ===== NAVIGATION LINKS ===== */
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

/* ===== TABLE STYLE ===== */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 14px;
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}

/* Header */
table th {
    background: #0A2A42;
    color: #ffffff;
    padding: 12px 8px;
    text-align: left;
    font-weight: 600;
}

/* Cell */
table td {
    padding: 10px 8px;
    border-bottom: 1px solid #E2E8F0;
}

/* Zebra effect */
table tr:nth-child(even) {
    background: #F4F4F4;
}

/* On hover */
table tr:hover {
    background: #E8F1F7;
    transition: 0.2s ease-in-out;
}

/* ===== ACTION LINKS ===== */
td a {
    font-size: 13px;
}

td span {
    font-size: 13px;
}

/* ===== RESPONSIVE ===== */
@media(max-width: 768px) {
    .container {
        width: 90%;
        padding: 20px;
    }

    table th, table td {
        font-size: 13px;
    }
}

    </style>
</head>

<body>
    <div class="container">
        <h1>Inventaris Barang</h1>
        <p>Selamat datang, <?= htmlspecialchars($_SESSION['user_name']) ?> <br>

        </p>
        <p>
            <a href="barang_add.php">Tambah Barang</a> |
            <a href="transaksi.php">Lihat Transaksi</a> | <a href="logout.php">Logout</a>
        </p>

        <table border="1" cellpadding="6" cellspacing="0">
            <tr>

                <th>Kode</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Tersedia</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>

                    <td><?= htmlspecialchars($row['kode']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= $row['tersedia'] ?></td>
                    <td><?= htmlspecialchars($row['lokasi']) ?></td>
                    <td>
                        <a href="barang_edit.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="barang_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus barang?')">Hapus</a> |
                        <?php if ($row['tersedia'] > 0): ?>
                            <a href="pinjam.php?id=<?= $row['id'] ?>">Pinjam</a>
                        <?php else: ?>
                            <span style="color:gray">Kosong</span>
                        <?php endif; ?>
                        <?php if ($row['jumlah'] > $row['tersedia']): ?>
                            | <a href="kembalikan.php?id=<?= $row['id'] ?>">Kembalikan</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>