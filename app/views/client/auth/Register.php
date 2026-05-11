<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<section class="min-h-[90vh] flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-slate-100">
        <div>
            <h2 class="text-center text-3xl font-extrabold text-slate-900">
                Tạo tài khoản mới
            </h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Gia nhập thế giới trang sức cao cấp Aurelia
            </p>
        </div>

        <div id="error" class="text-red-500 text-sm text-center font-medium"><?= $data['error'] ?? ''?></div>

        <form id="registerForm" class="mt-8 space-y-5" action="<?= URLROOT ?>/Auth/register" method="POST">
            <div>
                <label for="username" class="block text-sm font-medium text-slate-700">Tên đăng nhập</label>
                <input type="text" name="username" id="username" autocomplete="username" value="<?= $data['username'] ?? '' ?>" required
                       class="mt-1 block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm transition-all">
                <small id="username_msg" class="block mt-1 text-xs h-4"></small>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" id="email" autocomplete="email" value="<?= $data['email'] ?? '' ?>" required
                       class="mt-1 block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm transition-all">
                <small id="email_msg" class="block mt-1 text-xs h-4"></small>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Mật khẩu</label>
                <input type="password" name="password" id="password" autocomplete="new-password" required
                       class="mt-1 block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm transition-all">
                <small id="password_msg" class="block mt-1 text-xs h-4"></small>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-slate-700">Xác nhận mật khẩu</label>
                <input type="password" name="confirm_password" id="confirm_password" required
                       class="mt-1 block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm transition-all">
                <small id="confirm_msg" class="block mt-1 text-xs h-4"></small>
            </div>

            <div class="pt-2">
                <button type="submit" id="btn-register" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all shadow-lg uppercase tracking-wider">
                    Đăng ký ngay
                </button>
            </div>

            <p class="text-center text-sm text-slate-600">
                Đã có tài khoản? 
                <a href="<?= URLROOT ?>/Auth/login" class="font-bold text-amber-600 hover:text-amber-500 transition-colors">Đăng nhập ngay</a>
            </p>
        </form>
    </div>
</section>

<script>
    let validation = {
        username: false,
        email: false,
        password: false,
        confirm: false
    };

    async function checkValue(field, value, msgElementId) {
        const msgElement = document.getElementById(msgElementId);
        if (!value) {
            msgElement.innerText = "";
            return;
        }

        if (field === 'username') {
            const regex = /^[a-zA-Z0-9_]{3,50}$/;
            if (!regex.test(value)) {
                msgElement.innerText = "3-50 ký tự, không chứa ký tự đặc biệt!";
                msgElement.className = "block mt-1 text-xs h-4 text-red-500";
                validation.username = false;
                return;
            }
        }

        if (field === 'email') {
            const regex=/\S+@\S+\.\S+/;
            if (!regex.test(value)) {
                msgElement.innerText = "Email không hợp lệ!";
                msgElement.className = "block mt-1 text-xs h-4 text-red-500";
                validation.email = false;
                return;
            }
        }

        try {
            let formData = new FormData();
            formData.append(field, value);
            let endpoint = field === 'username' ? 'checkUsername' : 'checkEmail';
            let response = await fetch(`<?= URLROOT ?>/Auth/${endpoint}`, {
                method: 'POST',
                body: formData
            });

            let data = await response.json();
            if (data.exists) {
                msgElement.innerText = `${field === 'username' ? 'Tên đăng nhập' : 'Email'} đã tồn tại!`;
                msgElement.className = "block mt-1 text-xs h-4 text-red-500";
                validation[field] = false;
            } else {
                msgElement.innerText = "Hợp lệ";
                msgElement.className = "block mt-1 text-xs h-4 text-green-500";
                validation[field] = true;
            }            
        } catch (error) {
            console.error('Error:', error);
        }
    }

    let timer;
    let delay = 500;

    document.getElementById('username').addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(() => {
            checkValue('username', this.value, 'username_msg');
        }, delay);
    });

    document.getElementById('email').addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(() => {
            checkValue('email', this.value, 'email_msg');
        }, delay);
    });

    document.getElementById('password').addEventListener('input', function(){
        const msg = document.getElementById('password_msg');
        if (this.value.length < 6) {
            document.getElementById('confirm_password').disabled = true;
            msg.innerText = "Ít nhất 6 ký tự!";
            msg.className = "block mt-1 text-xs h-4 text-red-500";
            validation.password = false;
        } else {
            document.getElementById('confirm_password').disabled = false;
            msg.innerText = "Mật khẩu mạnh";
            msg.className = "block mt-1 text-xs h-4 text-green-500";
            validation.password = true;
        }
    });

    document.getElementById('confirm_password').addEventListener('input', function() {
        const pass = document.getElementById('password').value;
        const msg = document.getElementById('confirm_msg');
        
        if (this.value !== pass) {
            msg.innerText = "Mật khẩu không khớp!";
            msg.className = "block mt-1 text-xs h-4 text-red-500";
            validation.confirm = false;
        } else {
            msg.innerText = "Hợp lệ";
            msg.className = "block mt-1 text-xs h-4 text-green-500";
            validation.confirm = true;
        }
    });

    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-register');
        btn.disabled = true;
        
        const isReady = Object.values(validation).every(val => val === true);
        if (!isReady) {
            document.getElementById('error').innerText = "Vui lòng kiểm tra lại thông tin!";
            btn.disabled = false;
            return;
        }

        btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        
        let formData = new FormData(this);
        try {
            let response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                // If not redirected, check for error JSON
                try {
                    let result = await response.json();
                    if (result.error) {
                        document.getElementById('error').innerText = result.error;
                        btn.disabled = false;
                        btn.innerText = 'ĐĂNG KÝ NGAY';
                    }
                } catch(e) {
                    // Fallback
                    btn.disabled = false;
                    btn.innerText = 'ĐĂNG KÝ NGAY';
                }
            }
        } catch (error) {
            console.log('Error:', error);
            document.getElementById('error').innerText= "Đăng ký thất bại. Vui lòng thử lại sau.";
            btn.disabled = false;
            btn.innerText = 'ĐĂNG KÝ NGAY';
        }
    });
</script>