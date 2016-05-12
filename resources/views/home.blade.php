<html>
    <head>
        <title>demo</title>
        <link rel="stylesheet" href="packages/bower_components/bootstrap/dist/css/bootstrap.min.css" />
        <script type="text/javascript" src="packages/bower_components/jQuery/dist/jquery.min.js"></script>
    </head>
    <body>
        <div class="container">
        @if($errors->any())
                <ul class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
        @endif
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                 <form action="{{ URL('home/register') }}" class="" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <label for="username">用户名</label>
                    <input type="text" name="username" id="username"  class="form-control" placeholder="" required />
                </div>
                <div class="form-group">
                    <label for="password">密码</label>
                    <input type="password" name="password" id="password" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="mobile">手机号</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="img_captcha">图片验证码</label>
                    <input type="text" name="img_captcha" id="img_captcha" class="form-control" required />
                    <img id="captcha_img" src="{{$img_captcha}}" onclick="refresh_img_captcha()">
                </div>
                <div class="form-group">
                    <label for="sms_captcha">短信验证码</label>
                    <input type="text" name="sms_captcha" id="sms_captcha" class="form-control" required />
                    <a id='send_sms_captcha' class="btn btn-primary pull-right">发送短信验证码</a>
                </div>
                <div>
                    <button type="submit" class="btn btn-danger">注册</button>
                </div>
            </form>
            </div>
        </div>

        </div>
    </body>
</html>
<script type="text/javascript">
$(function(){
    $.ajaxSetup({
        data: { "_token": "{{csrf_token()}}" }
    });

    $("#send_sms_captcha").on('click', function() {
        var res = check_input_captcha();
        if(res == 1){
            if(wait==60) {
                var img_captcha = $("#img_captcha").val();
                var mobile = $('#mobile').val();
                 $.ajax({
                    url: '/home/send_captcha',
                    async: false,
                    type: 'POST',
                    data: {
                        'type': 'sms',
                        'mobile': mobile,
                        'img_captcha': img_captcha
                    },
                    success : function(data){
                       time_down($('#send_sms_captcha'));
                    }
                });
            }
        }else{
            alert("验证码错误，请重新输入");
        }
    });



})


    var wait=60;
    function time_down(o) {
        if (wait == 0) {
            o.attr("disabled",false);
            o.html("获取验证码");
            wait = 60;
        } else {
            o.attr("disabled", true);
            o.html("重新发送(" + wait + ")");
            wait --;
            setTimeout(function() {
                time_down(o)
            }, 1000);
        }
    }

    function check_input_captcha(){
        var img_captcha = $("#img_captcha").val();
        if(img_captcha == ""){
            return 0;
        }
        var result = 0;
        $.ajax({
            url: '/home/ajax_check_img_captcha',
            async: false,
            data: {
                'img_captcha': img_captcha,
            },
            success : function(data){
                console.log(data);
                if(data.result === 0){
                    // refresh_img_captcha();
                }
                result = data.result;
            }
        });
        return result;
    }

    function refresh_img_captcha(){
        $.ajax({
            url: '/home/ajax_get_img_captcha',
            async: false,
            success : function(data){
                $('#captcha_img').attr('src', data.src);
            }
        });
    }
</script>
