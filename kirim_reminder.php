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
        // $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;

        // PENGATURAN SERVER SMTP
        $mail->isSMTP();
        $mail->Host       = 'mail.symotech.id';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@symotech.id';
        $mail->Password   = 'Samirun@9999';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        // PENGIRIM & PENERIMA
        $mail->setFrom('info@symotech.id', 'Booking MUA');
        $mail->addAddress($tujuan);

        // KONTEN EMAIL
        $mail->isHTML(true);
        $mail->Subject = $judul;

        // Template HTML dengan struktur reminder
        $template = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background: #fdfdfd; padding: 20px; }
                .email-container { background: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); max-width: 600px; margin: auto; color: #333; }
                h2 { color: #db7093; }
                p { line-height: 1.6; font-size: 14px; }
                .footer { margin-top: 30px; font-size: 12px; color: #888; text-align: center; }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <h2>$judul</h2>
                <p>$pesan</p>
                <p style='margin-top: 20px;'>📌 Harap pastikan semua keperluan dan kesiapan Anda sebelum hari H. Jika ada perubahan jadwal atau pembatalan, segera hubungi pihak terkait.</p>
                <div class='footer'>&copy; " . date('Y') . " Booking MUA - Email ini dikirim otomatis, mohon tidak membalas langsung ke email ini.</div>
            </div>
        </body>
        </html>";

        $mail->Body = $template;
        $mail->AltBody = strip_tags($pesan);

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
