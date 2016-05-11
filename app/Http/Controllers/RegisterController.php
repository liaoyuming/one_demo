<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


        /* 短信校验 */
    public function send_verify_code()
    {
        $mobile = I('mobile');
        $type = I("type")?I("type"):"sms";
        $captcha = I("captcha");
        if (!Utility::IsMobile($mobile)) {
            json("send_sms_callback('','手机号码有误');", 'eval');
            return;
        }
        //必须要验证码
        if(!Utility::CaptchaCheck($captcha)){
            json("send_sms_callback('','验证码错误');", 'eval');
            return;
        }
        //限制发送必须经过至少一次页面
        $bind_code = Session::get("sms_bind_code", true);
        if (!$bind_code) {
            json("send_sms_callback('','请重新刷新页面尝试!');", 'eval');
            return;
        }

        $verify_code = rand(100000, 999999);
        Session::Set('sms_verify_code', $verify_code);

        $res = $this->_send_code($mobile, $verify_code, $type);
        if (!$res) {
            json("send_sms_callback('','短信无法送达，请及时联系灵析小助手！');", 'eval');
            return;
        }
        $bind_code = $this->_get_sms_bind_code();
        json("send_sms_callback('$bind_code', '');", 'eval');
    }

    private function _send_code($mobile, $code, $type="sms")
    {
        $content = '账户注册手机验证码：' . $code . '，感谢你的使用。';
        $count   = intval(Session::Get('send_verify_code_count'));
        if ($count>3) {
            // 发了三次还没收到，通知一下管理员
            raw_mail(C("notify_email"), '注册短信发送三次有误！！！请注意:'.$mobile);
        }

        $params['subdomain'] = 'jxdr';
        $params['sms_beian'] = '灵析';
        $type = ($type=='sms')?"yunpian":"yunpianvoice";
        $smsSender = new SmsFacade($type, 'yunpian', 'yunpian', $params);
        if($type=="yunpianvoice"){
            $content = $code;
        }
        $res = $smsSender->send($mobile, $content);
        if($res!==true) {
            raw_mail(C("notify_email"), '注册短信发送有误！！！请注意:'.$mobile);
        }
        Session::Set('send_verify_code_count', $count + 2);

        return true;
    }

    public function ajax_check_verify_code()
    {
        $code = trim(I('code'));

        $session_code = Session::Get('sms_verify_code');
        $result       = $session_code == $code ? 1 : 0;
        if ($result) {
            Session::Get('sms_verify_code');
        }

        Session::Set("has_checked_mobile_code", $result);
        return json("ajax_check_code_callback('$result')", "eval");
    }

    private function _get_sms_bind_code()
    {
        $sms_bind_code = md5(rand(100000, 999999));
        Session::Set('sms_bind_code', $sms_bind_code);
        return $sms_bind_code;
    }
}
