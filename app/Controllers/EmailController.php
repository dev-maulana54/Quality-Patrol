<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class EmailController extends Controller
{
    public function sendEmail()
    {
        // Nonaktifkan timeout
        set_time_limit(0);
        
        $config = [
            'protocol'     => 'smtp',
            'SMTPHost'     => 'smtp.gmail.com',
            'SMTPUser'     => 'lanayeager12@gmail.com',
            'SMTPPass'     => 'djsimmegtyywgbzn', // Pastikan ini benar
            'SMTPPort'     => 465,
            'SMTPCrypto'   => 'ssl',
            'SMTPTimeout'  => 30,
            'SMTPKeepAlive' => false,
            'mailType'     => 'html',
            'charset'      => 'utf-8',
            'wordWrap'     => true,
            'newline'      => "\r\n",
            'CRLF'         => "\r\n",
            'validate'     => true,
            'priority'     => 3,
            'SMTPAutoTLS'  => true, // Tambahkan ini
            'DSN'          => false
        ];

        $email = \Config\Services::email();
        $email->initialize($config);

        $email->setFrom('lanayeager12@gmail.com', 'Maulana');
        $email->setTo('maulana.saefulakbar06@gmail.com');
        $email->setSubject('Test Email CodeIgniter 4');
        $email->setMessage('<h1>Halo!</h1><p>Ini adalah email test dari CodeIgniter 4.</p>');

        if ($email->send()) {
            echo '✅ Email berhasil dikirim!';
        } else {
            echo '❌ Email gagal dikirim!<br><br>';
            echo '<pre>';
            echo $email->printDebugger(['headers', 'subject', 'body']);
            echo '</pre>';
        }
    }
    public function checkSSL()
{
    if (extension_loaded('openssl')) {
        echo "OpenSSL sudah aktif<br>";
        print_r(openssl_get_cert_locations());
    } else {
        echo "OpenSSL TIDAK aktif - Email tidak akan bekerja!";
    }
}
public function testConnection()
{
    echo "Testing koneksi ke Gmail SMTP...<br><br>";
    
    // Test Port 465 (SSL)
    echo "Test Port 465 (SSL): ";
    $smtp465 = @fsockopen('ssl://smtp.gmail.com', 465, $errno, $errstr, 10);
    if ($smtp465) {
        echo "✅ BERHASIL<br>";
        fclose($smtp465);
    } else {
        echo "❌ GAGAL - $errstr ($errno)<br>";
    }
    
    // Test Port 587 (TLS)
    echo "Test Port 587 (TLS): ";
    $smtp587 = @fsockopen('smtp.gmail.com', 587, $errno, $errstr, 10);
    if ($smtp587) {
        echo "✅ BERHASIL<br>";
        fclose($smtp587);
    } else {
        echo "❌ GAGAL - $errstr ($errno)<br>";
    }
    
    // Check OpenSSL
    echo "<br>OpenSSL Extension: ";
    if (extension_loaded('openssl')) {
        echo "✅ AKTIF<br>";
    } else {
        echo "❌ TIDAK AKTIF (INI MASALAHNYA!)<br>";
    }
    
    // Check allow_url_fopen
    echo "allow_url_fopen: ";
    if (ini_get('allow_url_fopen')) {
        echo "✅ ENABLED<br>";
    } else {
        echo "❌ DISABLED<br>";
    }
}
}