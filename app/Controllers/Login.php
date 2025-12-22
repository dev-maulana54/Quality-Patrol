<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        $data['title'] = "Login";
        if (session()->get('isLoggedIn')) {
            return redirect()->to('summary');
        }
        return view('auth/login', $data);
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
//     public function cek()
//     {
//         // Cek session spesifik
// if (session()->has('isLoggedIn')) {
//     echo "Session isLoggedIn ADA";
// } else {
//     echo "Session isLoggedIn TIDAK ADA";
// }
//     }
}
