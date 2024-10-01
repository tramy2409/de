<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "school_management");
$sql = "SELECT * FROM users WHERE role='student'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý học sinh</title>
</head>
<body>
    <h2>Danh sách học sinh</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
        </tr>
        <?php while($student = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $student['id']; ?></td>
            <td><?php echo $student['username']; ?></td>
        </tr>
        <?php } ?>
    </table>
    <a href="/HA/auth/logout.php">Đăng xuất</a>
</body>
</html>
