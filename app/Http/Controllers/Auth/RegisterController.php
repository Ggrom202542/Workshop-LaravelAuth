<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
        $this->middleware('guest');
    }

    function register(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ],
            [
                'name.required' => 'กรอกคำนำหน้าชื่อ - นามสกุล',
                'email.required' => 'กรอกอีเมล',
                'password.required' => 'สร้างรหัสผ่าน',
                'password.min' => 'รหัสผ่านอย่างน้อย 8 ตัวอักษร',
                'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
            ]
        );

        date_default_timezone_set('Asia/Bangkok');
        $date = date('Y-m-d H:i:s');

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => $date,
        ];

        DB::table('registers')->insert($data);
        return redirect()->route('login')->with('success', 'สมัครสมาชิกสำเร็จ');
    }
}