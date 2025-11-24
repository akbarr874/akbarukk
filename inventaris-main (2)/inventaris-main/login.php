<?php
// login.php
require_once 'koneksi.php';
require_once 'helpers.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, password, fullname FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['fullname'] ?? $username;
            header('Location: index.php');
            exit;
        }
    }
    $error = "Username atau password salah.";
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login - Inventaris</title>
    <link rel="stylesheet">
    <style>
        /* ===== RESET ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

/* ===== BODY ===== */
body {
    background: linear-gradient(135deg, #0A2A42, #3B6D8C);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ===== LOGIN CONTAINER ===== */
.container {
    background: #ffffff;
    width: 380px;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    animation: fadeIn 0.5s ease-out;
}

/* Animasi lembut */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ===== TITLE ===== */
.container h2 {
    text-align: center;
    font-size: 24px;
    color: #0A2A42;
    margin-bottom: 20px;
    font-weight: 700;
}

/* ===== LABEL ===== */
label {
    color: #0A2A42;
    font-weight: 500;
    font-size: 14px;
}

/* ===== INPUT ===== */
input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-top: 6px;
    margin-bottom: 18px;
    border-radius: 10px;
    border: 1px solid #BFCAD3;
    background-color: #F4F4F4;
    transition: 0.25s ease;
}

input:focus {
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

    </style>
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Username</label><br>
            <input type="text" name="username" required><br>
            <label>Password</label><br>
            <input type="password" name="password" required><br><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>