<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ApiTest extends Controller
{
    public function test_api()
{
    $url = 'https://portal3.incoe.astra.co.id/production_control_v2/public/api/login';

    $ch = curl_init($url);

    $postFields = [
        'username' => 'msa4368',
        'password' => 'msa4368',
    ];

    curl_setopt_array($ch, [
        CURLOPT_POST            => true,
        CURLOPT_POSTFIELDS      => $postFields, // ini akan jadi multipart/form-data otomatis
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_HEADER          => true,  // biar kita bisa lihat header + body
        CURLOPT_TIMEOUT         => 20,

        // SSL (sementara untuk masalah issuer)
        CURLOPT_SSL_VERIFYPEER  => false,
        CURLOPT_SSL_VERIFYHOST  => 0,

        // kadang API/WAF butuh user-agent
        CURLOPT_USERAGENT       => 'PostmanRuntime/7.36.0',
        CURLOPT_HTTPHEADER      => [
            'Accept: application/json',
        ],
    ]);

    $raw = curl_exec($ch);

    if ($raw === false) {
        $err = curl_error($ch);
        $no  = curl_errno($ch);
        curl_close($ch);

        return $this->response->setStatusCode(500)->setJSON([
            'ok'    => false,
            'error' => "{$no} : {$err}",
        ]);
    }

    $status     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $respHeader = substr($raw, 0, $headerSize);
    $body       = substr($raw, $headerSize);

    curl_close($ch);

    $decoded = json_decode($body, true);

    return $this->response->setJSON([
        'status'        => $status,
        'resp_headers'  => $respHeader,
        'body_len'      => strlen($body),
        'body_raw'      => $body,
        'is_json'       => (json_last_error() === JSON_ERROR_NONE),
        'json_error'    => json_last_error_msg(),
        'decoded'       => $decoded,
    ]);
}



}
