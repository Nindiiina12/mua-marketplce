<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Muat file PHPMailer
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

function kirimEmailPembatalan($tipe, $namaTujuan, $emailTujuan, $namaLawan, $tanggal, $waktu) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'mail.symotech.id';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@symotech.id';
        $mail->Password   = 'Samirun@9999';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        $mail->setFrom('info@symotech.id', 'Booking MUA');
        $mail->addAddress($emailTujuan);

        $mail->isHTML(true);

        // Siapkan konten berdasarkan tipe penerima (mua atau client)
        if ($tipe === 'mua') {
            $judul = "❌ Booking Dibatalkan oleh Pelanggan";
            $pesan = "
                Hai $namaTujuan,<br><br>
                Booking dari pelanggan <strong>$namaLawan</strong> pada:<br>
                📅 <strong>$tanggal</strong> jam <strong>$waktu</strong><br>
                Telah <strong>dibatalkan</strong> oleh pelanggan.<br><br>
                Silakan update jadwal Anda.<br><br>
                Salam,<br><strong>Tim Booking MUA</strong>
            ";
        } else {
            $judul = "❌ Konfirmasi Pembatalan Booking Anda";
            $pesan = "
                Hai $namaTujuan,<br><br>
                Anda telah membatalkan booking dengan <strong>$namaLawan</strong> pada:<br>
                📅 <strong>$tanggal</strong> jam <strong>$waktu</strong><br><br>
                Jika ini tidak disengaja, silakan lakukan booking ulang melalui aplikasi.<br><br>
                Salam,<br><strong>Tim Booking MUA</strong>
            ";
        }

        // Template HTML email
        $template = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background: #fdfdfd; padding: 20px; }
                .email-container { background: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); max-width: 600px; margin: auto; color: #333; }
                h2 { color: #db7093; }
                p { line-height: 1.6; }
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

        $mail->Subject = $judul;
        $mail->Body    = $template;
        $mail->AltBody = strip_tags($pesan);

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
