<h2>Đăng nhập</h2>

<?php if(isset($_GET['register']) && $_GET['register']=='success'): ?>
    <p>Xác thực thành công! Vui lòng đăng nhập.</p>
<?php endif; ?>

<p id="error"></p>

<form id="loginForm" action="<?= URLROOT ?>/Login" method="POST">
    <div>
        <label for="username">Tên đăng nhập:</label><br>
        <input type="text" name="username" id="username" autocomplete="username" required>
    </div>

    <div>
        <label for="password">Mật khẩu:</label><br>
        <input type="password" name="password" id="password" autocomplete="current-password" required>
    </div>

    <br>
    <button type="submit" id="btn-login">Đăng nhập</button>
    <p>Chưa có tài khoản? <a href="<?= URLROOT ?>/Register">Đăng ký tại đây</a></p>
</form>


<script>
$(function() {
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        const btn = $('#btn-login');
        btn.prop('disabled', true);
        const errorEl = $('#error');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(result) {
                if (result.error) {
                    errorEl.text(result.error);
                    btn.prop('disabled', false);
                } else if (result.success) {
                    window.location.href = result.redirect;
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                errorEl.text("Đã có lỗi xảy ra. Vui lòng thử lại sau!");
                btn.prop('disabled', false);
            }
        });
    });
});
</script>