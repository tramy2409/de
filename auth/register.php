<?php
session_start();

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email']; // Thêm email

    // Kiểm tra độ dài mật khẩu
    if (strlen($password) < 6) {
        echo "Mật khẩu phải có ít nhất 6 ký tự.";
        exit();
    }

    // Kiểm tra định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Địa chỉ email không hợp lệ.";
        exit();
    }

    // Kết nối cơ sở dữ liệu
    $conn = new mysqli("localhost", "root", "", "school_management");

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối không thành công: " . $conn->connect_error);
    }

    // Kiểm tra xem tên đăng nhập hoặc email đã tồn tại chưa
    $check_sql = "SELECT * FROM users WHERE username=? OR email=?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $check_result = $stmt_check->get_result();

    if ($check_result->num_rows > 0) {
        echo "Tên đăng nhập hoặc email đã tồn tại. Vui lòng chọn tên đăng nhập khác.";
    } else {
        // Mặc định vai trò là 'student'
        $role = 'student';

        // Thêm tài khoản mới (không sử dụng mã hóa)
        $stmt = $conn->prepare("INSERT INTO users (username, password, role, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $password, $role, $email); // Lưu mật khẩu dưới dạng văn bản thuần túy
        if ($stmt->execute()) {
            echo "Đăng ký thành công!";
        } else {
            echo "Có lỗi xảy ra: " . $stmt->error;
        }
    }

    // Đóng kết nối và các câu lệnh
    $stmt_check->close(); // Đóng câu lệnh kiểm tra
    if (isset($stmt)) {
        $stmt->close(); // Đóng câu lệnh thêm nếu đã được khởi tạo
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"], input[type="password"], input[type="email"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
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

        a {
            display: block;
            margin-top: 10px;
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            display: none;
            margin-top: 10px;
        }

        .valid {
            color: green;
        }

        .invalid {
            color: red;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Đăng ký tài khoản học sinh</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" id="password" name="password" placeholder="Mật khẩu" required oninput="checkPassword()">
            <div id="passwordMessage" class="message"></div>
            <input type="email" name="email" placeholder="Địa chỉ Gmail" required> <!-- Trường email -->
            <button type="submit" name="register">Đăng ký</button>
        </form>
        <a href="../index.php">Quay lại đăng nhập</a>
    </div>

    <script>
        function checkPassword() {
            var password = document.getElementById("password").value;
            var message = document.getElementById("passwordMessage");
            if (password.length >= 6) {
                message.textContent = "Mật khẩu hợp lệ!";
                message.className = "message valid";
                message.style.display = "block";
            } else {
                message.textContent = "Mật khẩu phải có ít nhất 6 ký tự.";
                message.className = "message invalid";
                message.style.display = "block";
            }
        }
    </script>
</body>
</html>


