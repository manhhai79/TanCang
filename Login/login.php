<?php
session_start();

// DATA CỨNG ĐỂ TEST
$users = [
    'admin' => ['password' => '123456', 'name' => 'Trần Văn Giám Đốc', 'role' => 'admin', 'role_name' => 'Giám đốc Cảng'],
    'staff' => ['password' => '123456', 'name' => 'Nguyễn Văn Nhân Viên', 'role' => 'staff', 'role_name' => 'Điều độ viên']
];

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u = $_POST['username'];
    $p = $_POST['password'];
    if (isset($users[$u]) && $users[$u]['password'] == $p) {
        $_SESSION['user_logged'] = true;
        $_SESSION['user_info'] = $users[$u];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Tài khoản hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Tân Cảng Sài Gòn</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-box">
        <img src="https://cdn.saigonnewport.com.vn/uploads/images/2021/11/09/logo-tcty-2020-01-618a8807195e3.png" alt="SNP Logo" class="logo-img">
        <h2>HỆ THỐNG QUẢN LÝ CÔNG TY TÂN CẢNG</h2>
        <p>Vui lòng đăng nhập để tiếp tục</p>

        <?php if($error): ?><div class="error-msg"><?= $error ?></div><?php endif; ?>

        <form method="POST" action="">
            <div class="input-group">
                <label>Tên đăng nhập</label>
                <input type="text" name="username" placeholder="admin hoặc staff" required>
            </div>
            <div class="input-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập mật khẩu (123456)" required>
            </div>
            <button type="submit">ĐĂNG NHẬP</button>
        </form>
        
        <div class="footer">&copy; 2026 Sai Gon Newport Corporation</div>
    </div>
</body>
</html>