<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<section class="min-h-[90vh] flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-slate-100">
        <div>
            <div class="text-center mb-4">
                <i class="fas fa-envelope-open-text text-amber-500 text-4xl"></i>
            </div>
            <h2 class="text-center text-3xl font-extrabold text-slate-900">
                Xác thực Email
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Một mã OTP gồm 6 chữ số đã được gửi đến email của bạn.
            </p>
        </div>

        <div id="error" class="text-red-500 text-sm text-center font-medium"></div>

        <form id="verifyForm" action="<?= URLROOT ?>/Register/verify_email" method="POST" class="mt-8 space-y-6">
            <div>
                <label for="otp" class="block text-sm font-medium text-slate-700 text-center">Nhập mã OTP</label>
                <input type="text" name="otp" id="otp" maxlength="6" autocomplete="one-time-code" required 
                       class="mt-2 block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 text-center text-2xl tracking-[0.5em] font-bold transition-all"
                       placeholder="••••••">
                <small id="otp_msg" class="block mt-2 text-xs h-4 text-center"></small>
            </div>
            
            <div>
                <button type="submit" id="btn-verify" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all shadow-lg uppercase tracking-wider">
                    Xác nhận
                </button>
            </div>
        </form>

        <div class="mt-6 border-t border-slate-200 pt-6">
            <p class="text-center text-sm text-slate-600 mb-4">Không nhận được mã?</p>
            <form id="resendForm" action="<?= URLROOT ?>/Register/resendOTP" method="POST">
                <button type="submit" id="btn-resend" 
                        class="w-full flex justify-center py-2 px-4 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all">
                    Gửi lại mã OTP
                </button>
                <small id="resend_msg" class="block mt-2 text-xs h-4 text-center"></small>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="<?= URLROOT ?>/Register" class="text-sm font-bold text-amber-600 hover:text-amber-500 transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại đăng ký
            </a>
        </div>
    </div>
</section>

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
                msgEl.text("Mã OTP không hợp lệ!").removeClass("text-green-500").addClass("text-red-500");
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
        const originalText = btn.text();
        btn.html('<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>');

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
                        btn.text(originalText);
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
                btn.text(originalText);
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
                    msgEl.text(result.error).removeClass("text-green-500").addClass("text-red-500");
                } else if (result.success) {
                    msgEl.text("Đã gửi lại OTP thành công!").removeClass("text-red-500").addClass("text-green-500");
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