<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bài học</title>
</head>
<body>
    <h2>Chào mừng <?php echo $_SESSION['user']['username']; ?>!</h2>
    <p>Đây là bài học dành cho học sinh.</p>
    <a href="/HA/auth/logout.php">Đăng xuất</a>
</body>
</html>
