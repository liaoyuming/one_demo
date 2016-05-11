<html>
    <head>
        <title>demo</title>
        <link rel="stylesheet" href="packages/bower_components/bootstrap/dist/css/bootstrap.min.css" />
        <script type="text/javascript" src=""></script>
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
                    <input type="text" name="username" id="username"  class="form-control" placeholder="" />
                </div>
                <div class="form-group">
                    <label for="password">密码</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="mobile">手机号</label>
                    <input type="text" name="mobile" id="mobile" class="form-control">
                </div>
                <div class="form-group">
                    <label for="image_captcha">图片验证码</label>
                    <input type="text" name="image_captcha" id="image_captcha" class="form-control">
                    <img src="">
                </div>
                <div class="form-group">
                    <label for="sms_captcha">短信验证码</label>
                    <input type="text" name="sms_captcha" id="sms_captcha" class="form-control">
                    <button class="btn btn-primary pull-right">发送短信验证码</button>
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
