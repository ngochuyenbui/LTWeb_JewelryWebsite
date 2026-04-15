<h2>Đăng nhập</h2>

<?php if(isset($_GET['register']) && $_GET['register']=='success'): ?>
    <p>Xác thực thành công! Vui lòng đăng nhập.</p>
<?php endif; ?>

<p id="error"></p>

<form id="loginForm" action="<?= URLROOT ?>/Auth/login" method="POST">
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
    <p>Chưa có tài khoản? <a href="<?= URLROOT ?>/Auth/register">Đăng ký tại đây</a></p>
</form>


<script>
document.getElementById('loginForm').addEventListener('submit', async function(e){
    e.preventDefault();
    const btn = document.getElementById('btn-login');
    btn.disabled = true;
    try{
        let response = await fetch(this.action, {
            method: 'POST',
            body: new FormData(this)
        })
        let result = await response.json();
        if (result.error) {
            document.getElementById('error').innerText = result.error;
            btn.disabled = false;
        } else if (result.success) {
            window.location.href = result.redirect;
        }
    } catch (error) {
        console.log('Error:', error);
        document.getElementById('error').innerText = "Đã có lỗi xảy ra. Vui lòng thử lại sau!";
        btn.disabled = false;
    }
});

</script>