<?php
// buat_admin.php (jalankan sekali)
$pw = password_hash('admin123', PASSWORD_DEFAULT);
echo $pw . PHP_EOL;
