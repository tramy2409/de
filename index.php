<?php
session_start();
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kết nối cơ sở dữ liệu
    $conn = new mysqli("localhost", "root", "", "school_management");

    // Kiểm tra đăng nhập
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;

        if ($user['role'] == 'admin') {
            header("Location: admin/admin_dashboard.php");
        } else {
            header("Location: student/student_dashboard.php");
        }
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu sai!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
            position: relative;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"], input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .password-container {
            position: relative;
            display: flex;
        }

        input[type="password"] {
            width: 100%;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        button {
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background-color: #2980b9;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
        }

        a {
            display: block;
            margin-top: 10px;
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>
        <!-- Hiển thị thông báo lỗi nếu có -->
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required autocomplete="off">
            
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Mật khẩu" required autocomplete="off">
                <span class="toggle-password" onclick="togglePassword()">👁️</span> <!-- Icon con mắt -->
            </div>
            
            <button type="submit" name="login">Đăng nhập</button>
        </form>
        <a href="auth/register.php">Đăng ký tài khoản</a>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var icon = document.querySelector(".toggle-password");
            // Nếu type là password thì đổi thành text và ngược lại
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.textContent = "🙈"; // Đổi biểu tượng khi mật khẩu đang hiển thị
            } else {
                passwordField.type = "password";
                icon.textContent = "👁️"; // Đổi lại biểu tượng khi mật khẩu bị ẩn
            }
        }
    </script>
</body>
</html>
