<?php

namespace App\Http\Channel;


class SmsYunpianAdapter
{

    private $apikey = '4111a07a9ba80416330138d3929f0034'; //可以数据库里面，然后读取
    private $sms_beian = '好玩表演';//备案签名，写在数据库里


    public function send($mobile, $content)
    {
        $count = count(explode(',', $mobile));
        if ($count < 1) {
            return false;
        } elseif ($count == 1) {
            $this->single_send($mobile, $content);
        } else {
            $this->multi_send($mobile, $content);
        }
    }

    //单条发送
    public function single_send($mobile, $content)
    {
        if (strpos($content, $this->sms_beian) === false) {
            $content = "【" . $this->sms_beian . "】" . $content;
        }
        $smsapi     = $this->yunpian_api_url(); //短信网关
        $sendurl    = $smsapi . "/sms/single_send.json";
        header("Content-Type:text/html;charset=utf-8");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));

        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* 设置超时时间*/
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        /* 设置通信方式 */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 取得用户信息
        $json_data = $this->get_user($ch,$this->apikey);
        $array = json_decode($json_data,true);

        $data=array(
            'text'=>$content,
            'apikey'=>$this->apikey,
            'mobile'=>$mobile
            );
        curl_setopt ($ch, CURLOPT_URL, $sendurl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        dd($data);
        $send_res = json_decode(curl_exec($ch),true);
        if($send_res['code'] == 0){
            return true;
        }
        return $this->yunpian_status($send_res['code']);
    }

    //todo
    //多条发送
    public function multi_send($mobile, $content)
    {
        return false;
    }

    public function get_user($ch)
    {
        $smsapi     = $this->yunpian_api_url(); //短信网关
        $sendurl    = $smsapi . "/user/get.json";

        curl_setopt ($ch, CURLOPT_URL, $sendurl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('apikey' => $this->apikey)));
        return curl_exec($ch);
    }

    public function yunpian_api_url()
    {
        return "https://sms.yunpian.com/v2";
    }


    public function yunpian_status($status_code)
    {
        $status_str = array(
            "0"     =>  "短信发送成功",
            "1"     =>  "请求参数缺失",
            "2"     =>  "请求参数格式错误",
            "3"     =>  "账户余额不足",
            "4"     =>  "关键词屏蔽",
            "5"     =>  "未找到对应id的模板",
            "6"     =>  "添加模板失败",
            "7"     =>  "模板不可用",
            "8"     =>  "同一手机号30秒内重复提交相同的内容",
            "9"     =>  "同一手机号5分钟内重复提交相同的内容超过3次",
            "10"    =>  "手机号黑名单过滤",
            "11"    =>  "接口不支持GET方式调用",
            "12"    =>  "接口不支持POST方式调用",
            "13"    =>  "营销短信暂停发送",
            "14"    =>  "解码失败",
            "15"    =>  "签名不匹配",
            "16"    =>  "签名格式不正确",
            "17"    =>  "24小时内同一手机号发送次数超过限制",
            "18"    =>  "签名校验失败",
            "19"    =>  "请求已失效",
            "20"    =>  "不支持的国家地区",
            "21"    =>  "解密失败",
            "22"    =>  "1小时内同一手机号发送次数超过限制",
            "23"    =>  "发往模板支持的国家列表之外的地区",
            "24"    =>  "添加告警设置失败",
            "25"    =>  "手机号和内容个数不匹配",
            "26"    =>  "流量包错误",
            "27"    =>  "未开通金额计费",
            "28"    =>  "运营商错误",
            "29"    =>  "超过频率",
            "-1"    =>  "非法的apikey",
            "-2"    =>  "API没有权限",
            "-3"    =>  "IP没有权限",
            "-4"    =>  "访问次数超限",
            "-5"    =>  "访问频率超限",
            "-50"   =>  "未知异常",
            "-51"   =>  "系统繁忙",
            "-52"   =>  "充值失败",
            "-53"   =>  "提交短信失败",
            "-54"   =>  "记录已存在",
            "-55"   =>  "记录不存在",
            "-57"   =>  "用户开通过固定签名功能，但签名未设置",
        );
        return $status_str[$status_code];
    }
}
