<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Input;
use Response;
use App\Http\Channel\SmsAdapter;

class HomeController extends Controller
{
    public function index()
    {

        return view('home');
    }

    public function register(RegisterRequest $request)
    {
        $input = $request->all();
        if ($input['sms_captcha'] != Session::get('sms_captcha')) {
            dd('验证码错误');
        }
        dd($input);
    }
    public function send_captcha($type = 'sms', $mobile)
    {
        $captcha = rand(100000, 999999);
        Session::Set('sms_captcha', $captcha, 1);
dd(Session::get('sms_captcha'));
        $content = ' 您的验证码是' . $code . '.(请在一小时内使用。）';
         // Session::Set('send_captcha_count', $count + 2);
        // $count   = intval(Session::Get('send_captcha_count'));
        // if ($count>3) {
        //     // todo  连续发送三次，得注意： mobile
        // }
// dd($content, $mobile, $code, $type);
        if ($type == 'sms') {
            $type = 'yunpian';
        }
        $smsSender = new SmsAdapter($type);
        $res = $smsSender->send($mobile, $content);
        if($res!==true) {
            // 报错
        }
    }

}
