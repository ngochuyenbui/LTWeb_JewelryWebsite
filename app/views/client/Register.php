<h2>Đăng ký tài khoản</h2>

<p id="error"><?= $data['error'] ?? ''?></p>

<form id="registerForm" action="<?= URLROOT ?>/Auth/register" method="POST">
    <div>
        <label for="username">Tên đăng nhập:</label><br>
        <input type="text" name="username" id="username" autocomplete="username" value="<?= $data['username'] ?? '' ?>" required>
        <small id="username_msg" style="display: block; height: 15px; font-size: 12px;"></small>
    </div>

    <div>
        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" autocomplete="email" value="<?= $data['email'] ?? '' ?>" required>
        <small id="email_msg" style="display: block; height: 15px; font-size: 12px;"></small>
    </div>

    <div>
        <label for="password">Mật khẩu:</label><br>
        <input type="password" name="password" id="password" autocomplete="new-password" required>
        <small id="password_msg" style="display: block; height: 15px; font-size: 12px;"></small>

    </div>

    <div>
        <label for="confirm_password">Xác nhận mật khẩu:</label><br>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <small id="confirm_msg" style="display: block; height: 15px; font-size: 12px;"></small>
    </div>

    <br>
    <button type="submit" id="btn-register">Đăng ký</button>
    <p>Đã có tài khoản? <a href="<?= URLROOT ?>/Auth/login">Đăng nhập ngay</a></p>
</form>

<script>
    let validation = {
        username: false,
        email: false,
        password: false,
        confirm: false
    };
    async function checkValue(field, value, msgElementId) {
        
        const msgElement = document.getElementById(msgElementId);
        // Kiem tra regex username
        if (field === 'username') {
            const regex = /^[a-zA-Z0-9_]{3,50}$/;
            if (!regex.test(value)) {
                msgElement.innerText = "Username 3-50 ký tự, không chứa khoảng trắng hoặc ký tự đặc biệt!";
                msgElement.style.color = "red";
                validation.username = false;
                return;
            }
        }
        // Kiem tra regex email
        if (field === 'email') {
            const regex=/\S+@\S+\.\S+/;
            if (!regex.test(value)) {
                msgElement.innerText = "Email không hợp lệ!";
                msgElement.style.color = "red";
                validation.email = false;
                return;
            }
        }         
        try {
            let formData = new FormData();
            formData.append(field, value);
            endpoint = field === 'username' ? 'checkUsername' : 'checkEmail';
            let response = await fetch(`<?= URLROOT ?>/Auth/${endpoint}`, {
                method: 'POST',
                body: formData
            });

            let data = await response.json();
            if (data.exists) {
                msgElement.innerText = `${field === 'username' ? 'Tên đăng nhập' : 'Email'} đã tồn tại!`;
                msgElement.style.color = "red";
                validation[field] = false;
            } else {
                msgElement.innerText = "";
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
            msg.innerText = "Mật khẩu phải có ít nhất 6 ký tự!";
            msg.style.color = "red";
            validation.password = false;

        } else{
            document.getElementById('confirm_password').disabled = false;
            msg.innerText = "";
            validation.password = true;
        }
    })
    document.getElementById('confirm_password').addEventListener('input', function() {
        const pass = document.getElementById('password').value;
        const msg = document.getElementById('confirm_msg');
        
        if (this.value !== pass) {
            msg.innerText = "Mật khẩu xác nhận không khớp!";
            msg.style.color = "red";
            validation.confirm = false;
        } else{
            msg.innerText = "";
            validation.confirm = true;
        }
    });
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        document.getElementById('btn-register').disabled = true;
        const isReady = Object.values(validation).every(val => val === true);
        if (!isReady) {
            document.getElementById('error').innerText = "Vui lòng kiểm tra lại thông tin đăng ký!";
            return;
        }
        let formData = new FormData(this);
        try {
            let response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });
            if (response.redirected) {
                window.location.href = response.url;
            }
        } catch (error) {
            console.log('Error:', error);
            document.getElementById('error').innerText= "Đăng ký thất bại. Vui lòng thử lại sau.";
        }
    });
</script>