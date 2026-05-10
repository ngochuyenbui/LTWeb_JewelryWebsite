<h2>Đăng ký tài khoản</h2>

<p id="error"><?= $data['error'] ?? ''?></p>

<form id="registerForm" action="<?= URLROOT ?>/Register" method="POST">
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
    <p>Đã có tài khoản? <a href="<?= URLROOT ?>/Login">Đăng nhập ngay</a></p>
</form>

<script>
    $(function() {
        let validation = {
            username: false,
            email: false,
            password: false,
            confirm: false
        };

        async function checkValue(field, value, msgElementId) {
            const msgElement = $(`#${msgElementId}`);

            // Client-side regex validation
            if (field === 'username') {
                const regex = /^[a-zA-Z0-9_]{3,50}$/;
                if (!regex.test(value)) {
                    msgElement.text("Username 3-50 ký tự, không chứa khoảng trắng hoặc ký tự đặc biệt!").css("color", "red");
                    validation.username = false;
                    return;
                }
            }

            if (field === 'email') {
                const regex = /\S+@\S+\.\S+/;
                if (!regex.test(value)) {
                    msgElement.text("Email không hợp lệ!").css("color", "red");
                    validation.email = false;
                    return;
                }
            }

            // Server-side check for existence
            const endpoint = field === 'username' ? 'checkUsername' : 'checkEmail';
            try {
                const data = await $.ajax({
                    url: `<?= URLROOT ?>/Register/${endpoint}`,
                    type: 'POST',
                    data: {
                        [field]: value
                    },
                    dataType: 'json'
                });

                if (data.exists) {
                    msgElement.text(`${field === 'username' ? 'Tên đăng nhập' : 'Email'} đã tồn tại!`).css('color', 'red');
                    validation[field] = false;
                } else {
                    msgElement.text("");
                    validation[field] = true;
                }
            } catch (error) {
                console.error('Error:', error);
                msgElement.text("Lỗi khi kiểm tra. Vui lòng thử lại.").css('color', 'red');
            }
        }

        let usernameTimer;
        let emailTimer;
        const delay = 500;

        $("#username").on('input', function() {
            clearTimeout(usernameTimer);
            const value = this.value;
            usernameTimer = setTimeout(() => {
                checkValue('username', value, 'username_msg');
            }, delay);
        });

        $("#email").on('input', function() {
            clearTimeout(emailTimer);
            const value = this.value;
            emailTimer = setTimeout(() => {
                checkValue('email', value, 'email_msg');
            }, delay);
        });

        $("#password").on('input', function() {
            if (this.value.length < 6) {
                $("#confirm_password").prop('disabled', true);
                $("#password_msg").text("Mật khẩu phải có ít nhất 6 ký tự!").css('color', 'red');
                validation.password = false;
            } else {
                $("#confirm_password").prop('disabled', false);
                $("#password_msg").text("");
                validation.password = true;
            }
            // Re-validate confirm password field
            $("#confirm_password").trigger('input');
        });

        $("#confirm_password").on('input', function() {
            const pass = $("#password").val();
            const msg = $("#confirm_msg");
            if (this.value !== pass) {
                msg.text("Mật khẩu xác nhận không khớp!").css('color', 'red');
                validation.confirm = false;
            } else {
                msg.text("");
                validation.confirm = true;
            }
        });

        $("#registerForm").submit(async function(e) {
            e.preventDefault();
            const btn = $("#btn-register").prop('disabled', true);

            // Manually trigger all validations and wait for them to complete
            await checkValue('username', $('#username').val(), 'username_msg');
            await checkValue('email', $('#email').val(), 'email_msg');
            $('#password').trigger('input');

            const isReady = Object.values(validation).every(val => val === true);

            if (!isReady) {
                $("#error").text("Vui lòng kiểm tra lại thông tin đăng ký!");
                btn.prop('disabled', false);
                return;
            } else {
                $("#error").text("");
            }

            let formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data, status, xhr) {
                    const redirectUrl = xhr.getResponseHeader('Location') || (data && data.redirectUrl);
                    if (redirectUrl) {
                        window.location.href = redirectUrl;
                    } else if (data && data.error) {
                        $('#error').text(data.error);
                        btn.prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('#error').text("Đăng ký thất bại. Vui lòng thử lại sau.");
                    btn.prop('disabled', false);
                }
            });
        });
    });
</script>