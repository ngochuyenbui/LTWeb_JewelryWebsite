<h2>Xác thực Email</h2>
<p>Một mã OTP gồm 6 chữ số đã được gửi đến email của bạn.</p>

<p id="error"></p>


<form id="verifyForm" action="<?= URLROOT ?>/Auth/verify_email" method="POST">
    <div>
        <label for="otp">Nhập mã OTP:</label><br>
        <input type="text" name="otp" id="otp"  maxlength="6" autocomplete="one-time-code" required>
        <small id="otp_msg" style="display: block; height: 15px; font-size: 12px;"></small>

    </div>
    
    <br>
    <button type="submit" id="btn-verify">Xác nhận</button>
</form>

<hr>
<p>Không nhận được mã?</p>
<form id="resendForm" action="<?= URLROOT ?>/Auth/resendOTP" method="POST">
    <button type="submit" id="btn-resend">Gửi lại mã OTP</button>
    <small id="resend_msg" style="display: block; height: 15px; font-size: 12px;"></small>

</form>

<br>
<a href="<?= URLROOT ?>/Auth/register">Đăng ký</a>

<script>
    let timer;
    let inputDelay = 500;
    
    
    document.getElementById('otp').addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(() => {
            const regex = /^\d{6}$/;
            const msgEl = document.getElementById('otp_msg');
            if (!regex.test(this.value)) {
                msgEl.innerText = "Mã OTP không hợp lệ!";
                msgEl.style.color = "red";
                validation = false;
                document.getElementById('btn-verify').disabled = true;
            } else {
                msgEl.innerText = "";
                validation = true;
                document.getElementById('btn-verify').disabled = false;
            }
        }, inputDelay);
    });

    document.getElementById('verifyForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        document.getElementById('btn-verify').disabled = true;
        const errorEl = document.getElementById('error');

        try {
            let response = await fetch(this.action, {
                method: 'POST',
                body: new FormData(this)
            });
            let result = await response.json();
            if (result.error){
                errorEl.innerText = result.error;
                if (result.redirect) {
                    setTimeout(() => {window.location.href = result.redirect;}, 2000);
                } else {
                    document.getElementById('btn-verify').disabled = false;
                }
            } else if (result.success) {
                if (result.redirect) {
                    window.location.href = result.redirect;
                }
                
            }
        } catch (error) {
            errorEl.innerText = "Xảy ra lỗi. Vui lòng thử lại sau.";
            document.getElementById('btn-verify').disabled = false;
        }
    });

    let resendDelay = 60000;
    document.getElementById('resendForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-resend');
        btn.disabled = true;
        let sec = 60;
        const timer = setInterval(() => {
            sec--;
            btn.innerText = `Gửi lại sau ${sec}s`;
            if(sec <= 0) {
                clearInterval(timer);
                btn.disabled = false;
                btn.innerText = "Gửi lại mã OTP";
            }
        }, 1000);
        try {
            let response = await fetch(this.action, { method: 'POST' });
            let result = await response.json();
            if (result.error) {
                document.getElementById('resend_msg').innerText = result.error;
                btn.disabled = false;
                return;
            }
        } catch (error) {
            console.error('Error:', error);
            errorEl.innerText = "Xảy ra lỗi. Vui lòng thử lại sau.";
            btn.disabled = false;
        }
    });
</script>