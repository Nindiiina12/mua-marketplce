<?php
// Impor kelas PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Muat file PHPMailer (sesuaikan path jika perlu)
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

function kirimEmail($tujuan, $judul, $pesan) {
    $mail = new PHPMailer(true);

    try {
        // Aktifkan untuk debugging jika terjadi masalah
        // $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER; 

        // PENGATURAN SERVER SMTP BARU
        // --------------------------------
        $mail->isSMTP();
        $mail->Host       = 'mail.symotech.id';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@symotech.id';
        $mail->Password   = 'Samirun@9999'; // Kata sandi Anda
        $mail->SMTPSecure = 'ssl';            // Gunakan 'ssl' untuk port 465
        $mail->Port       = 465;

        // PENGIRIM & PENERIMA
        // --------------------------------
        $mail->setFrom('info@symotech.id', 'Booking MUA'); // Alamat pengirim
        $mail->addAddress($tujuan); // Alamat penerima

        // KONTEN EMAIL
        // --------------------------------
        $mail->isHTML(true);
        $mail->Subject = $judul;
        
        // Template email (sama seperti sebelumnya)
        $template = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background: #fdfdfd; padding: 20px; }
                .email-container { background: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); max-width: 600px; margin: auto; color: #333; }
                h2 { color: #db7093; }
                p { line-height: 1.5; }
                .footer { margin-top: 30px; font-size: 12px; color: #888; text-align: center; }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <h2>$judul</h2>
                <p>$pesan</p>
                <div class='footer'>&copy; " . date('Y') . " Booking MUA - Email ini dikirim otomatis.</div>
            </div>
        </body>
        </html>";
        
        $mail->Body = $template;
        $mail->AltBody = strip_tags($pesan);

        $mail->send();
        return true;
        
    } catch (Exception $e) {
        // Log error untuk debugging
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>