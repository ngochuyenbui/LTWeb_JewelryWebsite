<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail {
    public static function sendOTP($email, $otp) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();                                            
            $mail->Host       = $_ENV['MAIL_HOST'];                    
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = $_ENV['MAIL_USER'];                     
            $mail->Password   = $_ENV['MAIL_PASS'];                               
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
            $mail->Port       = $_ENV['MAIL_PORT'];   
                             

            $mail->setFrom($_ENV['MAIL_USER'], $_ENV['SITENAME']);
            $mail->addAddress($email);     
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Mã xác nhận OTP - ' . $_ENV['SITENAME'];
            $mail->Body    = "Mã OTP của bạn là: $otp. Mã có hiệu lực trong 5 phút.";
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('PHPMailer error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}