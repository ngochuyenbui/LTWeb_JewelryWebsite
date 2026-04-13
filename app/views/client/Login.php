<h2>Đăng nhập</h2>

<?php if(isset($_GET['success'])): ?>
    <p>Xác thực thành công! Vui lòng đăng nhập.</p>
<?php endif; ?>

<?php if(!empty($data['error'])): ?>
    <p"><?= $data['error'] ?></p>
<?php endif; ?>

<form action="<?= URLROOT ?>/Auth/login" method="POST">
    <div>
        <label for="username">Tên đăng nhập:</label><br>
        <input type="text" name="username" id="login-username" required>
    </div>

    <div>
        <label for="password">Mật khẩu:</label><br>
        <input type="password" name="password" id="login-password" required>
    </div>

    <br>
    <button type="submit" id="btn-login">Đăng nhập</button>
    <p>Chưa có tài khoản? <a href="<?= URLROOT ?>/Auth/register">Đăng ký tại đây</a></p>
</form>