<h2>Xác thực Email</h2>
<p>Một mã OTP gồm 6 chữ số đã được gửi đến email của bạn.</p>

<p id="error"></p>


<form id="verifyForm" action="<?= URLROOT ?>/Register/verify_email" method="POST">
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
<form id="resendForm" action="<?= URLROOT ?>/Register/resendOTP" method="POST">
    <button type="submit" id="btn-resend">Gửi lại mã OTP</button>
    <small id="resend_msg" style="display: block; height: 15px; font-size: 12px;"></small>

</form>

<br>
<a href="<?= URLROOT ?>/Register">Đăng ký</a>

<script>
$(function() {
    let otpTimer;
    const inputDelay = 500;

    $('#otp').on('input', function() {
        clearTimeout(otpTimer);
        const value = this.value;
        otpTimer = setTimeout(() => {
            const regex = /^\d{6}$/;
            const msgEl = $('#otp_msg');
            
            if (!regex.test(value)) {
                msgEl.text("Mã OTP không hợp lệ!").css("color", "red");
                $('#btn-verify').prop('disabled', true);
            } else {
                msgEl.text("");
                $('#btn-verify').prop('disabled', false);
            }
        }, inputDelay);
    });

    $('#verifyForm').submit(function(e) {
        e.preventDefault();
        const btn = $('#btn-verify');
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
                    if (result.redirect) {
                        setTimeout(() => { window.location.href = result.redirect; }, 2000);
                    } else {
                        btn.prop('disabled', false);
                    }
                } else if (result.success) {
                    if (result.redirect) {
                        window.location.href = result.redirect;
                    }
                }
            },
            error: function(xhr, status, error) {
                errorEl.text("Xảy ra lỗi. Vui lòng thử lại sau.");
                btn.prop('disabled', false);
            }
        });
    });

    $('#resendForm').submit(function(e) {
        e.preventDefault();
        const btn = $('#btn-resend');
        const msgEl = $('#resend_msg');
        const errorEl = $('#error');
        
        btn.prop('disabled', true);
        let sec = 60;
        const intervalTimer = setInterval(() => {
            sec--;
            btn.text(`Gửi lại sau ${sec}s`);
            if (sec <= 0) {
                clearInterval(intervalTimer);
                btn.prop('disabled', false);
                btn.text("Gửi lại mã OTP");
            }
        }, 1000);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            success: function(result) {
                if (result.error) {
                    msgEl.text(result.error).css("color", "red");
                } else if (result.success) {
                    msgEl.text("Đã gửi lại OTP thành công!").css("color", "green");
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                errorEl.text("Xảy ra lỗi. Vui lòng thử lại sau.");
            }
        });
    });
});
</script>