<h2>Xác thực Email</h2>
<p>Một mã OTP gồm 6 chữ số đã được gửi đến email của bạn.</p>

<?php if(!empty($data['error'])): ?>
    <p><?= $data['error'] ?></p>
<?php endif; ?>

<form action="<?= URLROOT ?>/Auth/verify_email" method="POST">
    <div>
        <label for="otp">Nhập mã OTP:</label><br>
        <input type="text" name="otp" id="otp" maxlength="6" required>
    </div>
    
    <br>
    <button type="submit" id="btn-verify">Xác nhận</button>
</form>

<hr>
<p>Không nhận được mã?</p>
<form action="<?= URLROOT ?>/Auth/resendOTP" method="POST">
    <button type="submit" id="btn-resend">Gửi lại mã OTP</button>
</form>

<br>
<a href="<?= URLROOT ?>/Auth/register">Quay lại trang đăng ký</a>