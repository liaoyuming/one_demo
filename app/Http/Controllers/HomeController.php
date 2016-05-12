<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SmsSendRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Input;
use Response;
use App\Http\Channel\SmsAdapter;
use Gregwar\Captcha\CaptchaBuilder;

class HomeController extends Controller
{
    public function index()
    {
        $captcha_src = $this->_get_img_captcha();
        return view('home')->with(['img_captcha' => $captcha_src]);
    }

    public function ajax_get_img_captcha()
    {
        $captcha_src = $this->_get_img_captcha();
        return Response::json(['src' => $captcha_src]);
    }

    private function _get_img_captcha()
    {
        $builder = new CaptchaBuilder;
        $builder->build(100, 40);
        Session::set('img_captcha', $builder->getPhrase());
        return $builder->inline();
    }

    public function register(RegisterRequest $request)
    {
        $input = $request->all();
        if ($input['sms_captcha'] != Session::get('sms_captcha')) {
            dd('验证码错误');
        }
        dd($input);
    }
    public function send_captcha(SmsSendRequest $request)
    {
        $type = $request->get('type') ? $request->get('type') : 'sms';
        $mobile = $request->get('mobile');
        if ($type == 'sms') {
            $this->_send_sms_captcha($type, $mobile);
        }
    }


    public function _send_sms_captcha($type, $mobile)
    {
        if (!$type || !$mobile) {
            return false;
        }
        $captcha = rand(100000, 999999);
        Session::set('sms_captcha', $captcha);
    // dd(rand(100, 1100), Session::get('sms_captcha'));
        $content = ' 您的验证码是' . $captcha . '.(请在一小时内使用。）';
        // Session::Set('send_captcha_count', $count + 2);
        // $count   = intval(Session::Get('send_captcha_count'));
        // if ($count>3) {
        //     // todo  连续发送三次，得注意： mobile
        // }

        if ($type == 'sms') {
            $type = 'yunpian';
        }
        $smsSender = new SmsAdapter($type);
        $res = $smsSender->send($mobile, $content);
        if($res!==true) {
            // 报错
        }
    }

    public function ajax_check_img_captcha(Request $request)
    {
        $img_captcha = $request->get('img_captcha');
        $result = (strtolower($img_captcha) == strtolower(Session::get("img_captcha")) && $img_captcha);
        $result = $result == true ? 1 : 0;
        return Response::json(['result' => $result]);
    }

}
