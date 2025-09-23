<?php
include 'koneksi.php';
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $login = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE username='$username' AND password='$password'");
    $cek   = mysqli_num_rows($login);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($login);

        // role admin
        if ($data['role'] == "admin") {
            $_SESSION['admin'] = $username;
            header("Location: dashboard.php");
            exit();

        // role pelanggan
        } elseif ($data['role'] == "pelanggan") {
            $_SESSION['user']    = $data['username'];
            $_SESSION['nama']    = $data['nama'];
            $_SESSION['id_user'] = $data['id'];
            header("Location: index.php");
            exit();
        }

    } else {
        echo "Username dan Password salah!";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <style>
        /* Styling sama seperti yang kamu buat sebelumnya */
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #f0f4f7, #d9e2ec);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background-color: white;
            padding: 40px 30px;
            width: 100%;
            max-width: 380px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }
        .form-group {
            margin-bottom: 18px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: 0.3s;
            box-sizing: border-box;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #28a745;
            outline: none;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        input[type="submit"]:hover {
            background: #218838;
        }
        .register-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            font-size: 14px;
            color: #333;
        }
        .register-link:hover {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required autocomplete="off" />
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required />
            </div>
            <input type="submit" name="login" value="Login" />
        </form>

        <a class="register-link" href="register.php">Belum punya akun? Daftar di sini</a>
    </div>
</body>
</html>
