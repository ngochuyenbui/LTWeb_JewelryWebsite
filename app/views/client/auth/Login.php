<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<section class="min-h-[90vh] flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-slate-100">
        <div>
            <div class="text-center mb-4">
                <i class=\"fas fa-gem text-amber-500 text-4xl\"></i>
            </div>
            <h2 class="text-center text-3xl font-extrabold text-slate-900">
                Chào mừng trở lại
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Đăng nhập để tiếp tục khám phá Aurelia
            </p>
        </div>

        <?php if(isset($_GET['register']) && $_GET['register']=='success'): ?>
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                <p class="text-sm text-green-700 font-medium">Đăng ký thành công! Vui lòng đăng nhập.</p>
            </div>
        <?php endif; ?>

        <div id="error" class="text-red-500 text-sm text-center font-medium"></div>

        <form id="loginForm" class="mt-8 space-y-6" action="<?= URLROOT ?>/Login" method="POST">
            <div class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-slate-700">Tên đăng nhập</label>
                    <input type="text" name="username" id="username" autocomplete="username" required
                           class="mt-1 block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm transition-all"
                           placeholder="Nhập tài khoản của bạn">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Mật khẩu</label>
                    <input type="password" name="password" id="password" autocomplete="current-password" required
                           class="mt-1 block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm transition-all"
                           placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300 rounded cursor-pointer">
                    <label for="remember-me" class="ml-2 block text-sm text-slate-700 cursor-pointer"> Ghi nhớ tôi </label>
                </div>
                <div class="text-sm">
                    <a href="#" class="font-medium text-amber-600 hover:text-amber-500">Quên mật khẩu?</a>
                </div>
            </div>

            <div>
                <button type="submit" id="btn-login"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all shadow-lg uppercase tracking-wider">
                    Đăng nhập
                </button>
            </div>

            <p class="text-center text-sm text-slate-600 mt-4">
                Chưa có tài khoản?
                <a href="<?= URLROOT ?>/Register" class="font-bold text-amber-600 hover:text-amber-500 transition-colors">Đăng ký ngay</a>
            </p>
        </form>
    </div>
</section>

<!-- <script>
document.getElementById('loginForm').addEventListener('submit', async function(e){
    e.preventDefault();
    const btn = document.getElementById('btn-login');
    const errorDiv = document.getElementById('error');

    // Hiệu ứng Loading
    btn.disabled = true;
    const originalText = btn.innerText;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

    try {
        let response = await fetch(this.action, {
            method: 'POST',
            body: new FormData(this)
        });

        let result = await response.json();

        if (result.error) {
            errorDiv.innerText = result.error;
            btn.disabled = false;
            btn.innerText = originalText;
        } else if (result.success) {
            window.location.href = result.redirect;
        }
    } catch (error) {
        console.error('Error:', error);
        errorDiv.innerText = "Lỗi kết nối máy chủ!";
        btn.disabled = false;
        btn.innerText = originalText;
    }
});
</script> -->

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