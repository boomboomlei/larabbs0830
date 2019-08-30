<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{

    use VerifiesEmails;

 
    protected $redirectTo = '/';


    public function __construct()
    {
        $this->middleware('auth');//有的控制器动作都需要登录后才能访问
        $this->middleware('signed')->only('verify'); //设定了 只有 verify 动作使用 signed 中间件进行认证
        $this->middleware('throttle:6,1')->only('verify', 'resend');//对 verify 和 resend 动作做了频率限制,访问频率是 1 分钟内不能超过 6 次。
    }
}
