# Template Laravel Framework 10.x Authentication

เทมเพลต Laravel Framework 10.x Authentication สำหรับการสร้างโปรเจคของนักพัฒนาระบบที่มีการ Multi Authentication ( Register & Login ) สำหรับการแบ่งบทบาท ( Roles ) ผู้ใช้งานภายในระบบ โดยมีการแบ่ง 2 ตำแหน่งคือ
- ผู้ดูแลระบบ
- ผู้ใช้งานทั่วไป

โดยสรุปเนื้อหา และถอดบทเรียนมาจาก
- [พัฒนาเว็บด้วย Laravel Framework 10.x | สำหรับผู้เริ่มต้น [FULL COURSE]](https://youtu.be/64aycSVCvWA?si=Dtim5qTFNd8PbTC-) ซึ่งสอนเกี่ยวกับพื้นฐานการใช้ Laravel Framework 10.x แบบละเอียด
- [สอน Laravel 8 สร้างระบบ Multi Authentication ( Register & Login )](https://youtu.be/DfqdO1_cNV8?si=2dGF9S_OwBEds2sT) สอนเกี่ยวกับการแบ่งบทบาทการเข้าถึงระบบนั้น ๆ ผ่านการ Login และการสมัครเข้าใช้งาน ซึ่งเป็นการอธิบายในส่วนของ Laravel Framework 8.x 

ทั้งนี้ ผู้จัดทำได้นำทั้ง 2 บทเรียนมาประยุกต์ใช้ให้มีความเข้ากันได้ เป็นที่เรียบร้อย รวมถึงการทำ Errors Page Template เพื่อให้ง่ายต่อการพัฒนา โดยถอดบทเรียนเกี่ยวกับ Errors Pages มาจาก
- [Laravel 10 custom error page tutorial |laravel tutorial | Laravel 10 | Laravel error 500 | error 404](https://youtu.be/-H4392Jkg00?si=QC8ZM-n4dsao2wYJ)

โดยเทมเพลตนี้ได้จัดเตรียมสภาพแวดล้อมไว้ครบแล้ว และเพิ่มคำอธิบายถึงการสร้างต่าง ๆ ไว้ภายในบทความนี้ ผู้พัฒนาสามารถอ่านรายละเอียด ศึกษาทำความเข้าใจ ได้ตามขั้นตอนดังนี้

1. การสร้างโปรเจคเริ่มต้น
2. การทำ Multi Authentication ( Register & Login )
    - ตรวจสอบ / อนุมัติการลงทะเบียน ก่อนใช้งาน
3. การสร้าง Errors Page สำหรับระบบ

สุดท้ายผู้พัฒนาสามารถทำตามขั้นตอน ตามความเหมาะสม เพื่อให้เข้ากับสภาพแวดล้อมกับโปรเจคนั้น ๆ ของผู้พัฒนา

## 1. การสร้างโปรเจคเริ่มต้น

1. ก่อนสร้างโปรเจ็กต์ Laravel แรกของคุณ โปรดตรวจสอบว่าเครื่องของคุณมีการติดตั้ง PHP และ [Composer](https://getcomposer.org/) นอกจากนี้ แนะนำการติดตั้ง [Node.js](https://nodejs.org/en) จากนั้น run คำสั่ง บน console ของคุณ

```console
composer create-project "laravel/laravel:^10.0" example-app
```

2. เข้าถึง Folder โปรเจคของคุณและ run คำสั่งต่อไปนี้ เพื่อความมั่นใจ

```console
composer global require laravel/installer
```

## 2. การทำ Multi Authentication ( Register & Login )

1. สร้างฐานข้อมูลและตั้งค่า

ในส่วนของการสร้างฐานข้อมูลนั้น ทางผู้จัดทำได้ใช้ตัว จำลอง [XAMPP](https://www.apachefriends.org/download.html) เปิดตัวจำลองและสร้างฐานข้อมูลบน phpMyAdmin และนำชื่อใส่ลงในไฟล์ .env 

```.env
DB_DATABASE=workshop_laravelauth
```

2. แก้ไขไฟล์ 2014_10_12_000000_create_users_table.php ในโฟลเดอร์ migrations

โดยการเพิ่ม ลงใน public function up()
```php
$table->boolean('is_admin')->nullable();
```
ผลลัพธ์

```php
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->boolean('is_admin')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });
}
```
3. แก้ไข Model user ในโฟลเดอร์ Modes เพิ่ม 'is_admin'

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'is_admin',
];
```

4. run คำสั่งบน Terminal หรือ Console
```console
php artisan migrate
```

5. ติดตั้ง Authentication ตามลำดับ

```console
composer require Laravel/ui
```

```console
php artisan ui bootstrap --auth
```

```console
npm install
```

```console
npm run build
```

6. สร้าง middleware IsAdmin ใช้คำสั่ง run คำสั่งบน Terminal หรือ Console

```console
php artisan make:middleware IsAdmin
```

7. แก้ไข ไฟล์ IsAdmin middleware
```php
public function handle(Request $request, Closure $next): Response
{
    if (auth()->user()->is_admin == 1) {
        return $next($request);
    }

    return redirect('home');
}
```

8. แก้ไข ไฟล์ Kernel.php เพิ่มคำสั่งเชื่อต่อ class middleware IsAdmin
```php
protected $middlewareAliases = [
        // ...
        'is_admin' => \App\Http\Middleware\IsAdmin::class,
    ];
```

9. สร้าง AdminController ใช้สำหรับการควบคุมการแสดง การทำงาน ในส่วนของแอดมิน ผู้ดูแลระบบ เมื่อติดต่อกับฐานข้อมูล
```console
php artisan make:controller AdminController
```

10. ทำการเพิ่มคำสั่ง Authentication ให้กับ AdminController เพื่อกำหนดการเข้าถึงและตรวจสอบการเข้าสู่ระบบ
```php
public function __construct()
{
    $this->middleware('auth');
}
```

11. กำหนด Route ฝั่งของแอดมินและผู้ใช้งาน ไฟล์ web.php

- เรียกใช้ Auth และ Controller ที่จำเป็นลงในไฟล์ web.php 
```php
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
```

- กำหนดเส้นทางที่ใช้ในการ login และ register ซึ่งในการติดตั้งนั้น ทาง Laravel Authentication มีกำหนดให้ก่อนแล้ว แต่ไม่แสดงเส้นทางใน Route ให้ผู้พัฒนาเห็น ผู้จัดทำจึงเพิ่มไว้เพื่อตวรสอบ และให้สามารถปรับแก้ไขได้ในภายหลัง
```php
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});


Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
```


- สร้างไฟล์ สำหรับผู้ดูแลระบบ ภายใน folder views ทั้งนี้ผู้จัดทำ เริ่มสร้างโฟลเดอร์แยกต่างหากสำหรับผู้ดูแลระบบ ภายในโฟลเดอร์ views/admin/dashboard.blade.php เป็นไฟล์หน้าแรกของผู้ดูแลระบบ 
- กำหนด Route ของผู้ดูแลระบบ ให้มีการจัดกลุ่มการเรียกใช้ middleware is_admin ซึ่ง Route ที่อยู่ในกลุ่มนี้ จะกำหนดให้คนที่มีสิทธิเป็นผู้ดูแลระบบเท่านั้น ที่เข้าใช้งานได้
```php
Route::group(['middleware' => 'is_admin'], function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
});
```
- ในส่วนของผู้ใช้งาน Laravel Authentication กำหนดเส้นทางไว้เรียบร้อยแล้ว
```php
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
```

ผลลัพธ์ ทั้งหมด
```php
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'is_admin'], function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
});
```

12. กำหนด function ใน class ของ AdminController เพื่อเรียกใช้ไฟล์ views/admin/dashboard.blade.php สำหรัยผู้ดูแลระบบ ในส่วนของผู้ใช้งาน Laravel Authentication กำหนดให้แล้วใน HomeController

```php
public function dashboard()
{
    return view('admin.dashboard');
}
```

ผลลัพธ์ AdminController
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
```

13. ปรับแต่งการ Login ในไฟล์ LoginController.php อยู่ในโฟลเดอร์ Controller/Auth/LoginController.php

- นำเข้า เพื่อใช้ในการรับค่าจากผู้ใช้งานเมื่อทำการกรอกข้อมูล Login
```php
use Illuminate\Http\Request;
```

- สร้างฟังก์ชันการ login ทำการตรวจสอบผู้ใช้งานเป็นผู้ดูแลระบบหรือไม่ และกำหนดเส้นทางผู้ใช้ไปยังไฟล์หน้าเพจต่าง ๆ
```php
public function login(Request $request)
{
    $input = $request->all();

    $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))) {
        if (auth()->user()->is_admin == 1) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('home');
        }
    } else {
        return redirect('/login')->with('error', 'ชื่อผู้ใช้และรหัสผ่านไม่ถูกต้อง');
    }
}
```

### ตรวจสอบ / อนุมัติการลงทะเบียน ก่อนเข้าใช้งาน

---
ทำการทดสอบก่อน 1 ครั้ง ให้ผู้พัฒนาทำการลงทะเบียนและกำหนดค่า is_admin ของตนให้เป็น 1 เพื่อกำหนดเป็นผู้ดูแลระบบ ในระหว่างนี้ ทางผู้จัดทำได้ปรับแต่งหน้า login.blade.php และ register.blade.php ให้เป็นภาษาไทย รวมไปถึงนำบางอย่างออก ดังนี้
- ลืมรหัสผ่าน
- nav tag ในไฟล์ app.blade.php โฟลเดอร์ layouts

ผู้พัฒนาปรับแต่ง login.blade.php และ register.blade.php ตามความต้องการ เมื่อผู้พัฒนาทำการ register ข้อมูลแล้ว ข้อมูลจะอยู่ในตาราง users ในฐานข้อมูล ทำการปรับ is_admin เป็น 1 เราจะเริ่มขั้นตอนถัดไป ในการจัดการ ตรวจสอบ / อนุมัติการลงทะเบียน ก่อนเข้าใช้งาน

14. ทำการสร้าง model และ migration สำหรับเก็บข้อมูลการลงทะเบียน
```console
php artisan make:model registers -m
```

- ปรับแต่งไฟล์ migration
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registers');
    }
};
```

-  run คำสั่งบน Terminal หรือ Console
```console
php artisan migrate
```

15. ปรับแต่ง Controller/Auth/register.blade.php

- นำเข้า

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
```

- เพิ่มฟังก์ชันเกี่ยวกับการเก็บข้อมูลการลงทะเบียนเข้าใช้งาน มีการตรวจสอบข้อมูล เมื่อมีการกรอกฟอร์มจากผู้ใช้งาน ( Request $request ) โดยการ validate ข้อมูล
```php
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
```

ผลลัพธ์
```php
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
```

เสร็จสิ้นการทำ Multi Authentication ( Register & Login ) ผู้พัฒนาสมามารถปรับแต่งได้ตามต้องการรวมถึงการอนุมัติการลงทะเบียน ได้ผ่านหน้าต่างของผู้ดูแลระบบในภายหลัง

## การสร้าง Errors Page สำหรับระบบ

1. run คำสั่งบน Terminal หรือ Console
```console
php artisan vendor:publish --tag=laravel-errors
```

Laravel ทำการเพิ่มไฟล์หน้าต่าง Error ต่าง ๆ ให้ภายใน folder views

## สนับสนุน

ติดต่อหรือสนับสนุนผ่าน, อีเมล prapreut.1803@gmail.com ขอบคุณครับ.
