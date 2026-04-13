<h2>Đăng ký tài khoản</h2>

<?php if(!empty($data['error'])): ?>
    <p><?= $data['error'] ?></p>
<?php endif; ?>

<form action="<?= URLROOT ?>/Auth/register" method="POST">
    <div>
        <label for="username">Tên đăng nhập:</label><br>
        <input type="text" name="username" id="username" value="<?= $data['username'] ?? '' ?>" required>
    </div>

    <div>
        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" value="<?= $data['email'] ?? '' ?>" required>
    </div>

    <div>
        <label for="password">Mật khẩu:</label><br>
        <input type="password" name="password" id="password" required>
    </div>

    <div>
        <label for="confirm_password">Xác nhận mật khẩu:</label><br>
        <input type="password" name="confirm_password" id="confirm_password" required>
    </div>

    <br>
    <button type="submit" id="btn-register">Đăng ký</button>
    <p>Đã có tài khoản? <a href="<?= URLROOT ?>/Auth/login">Đăng nhập ngay</a></p>
</form>