<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Đăng ký</title>
  <link rel="stylesheet" href="{{asset('css/login_style/bootstap.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/login_style/login_style.css')}}">
</head>

<body class="login-area">
<div class="main-content- dark-color-overlay  bg-img h-100vh" style="background-image: url(img/bg-img/11.jpg)">
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-md-7 col-lg-5 mx-auto">
                <div class="middle-box">
                    <div class="card">
                        <div class="card-body login-padding">
                            <h4 class="font-28 mb-30">Đăng ký tài khoản</h4>

                            <form action="{{route('postRegister')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label class="float-left" for="username">Tên đăng nhập</label>
                                    <input class="form-control" type="text" name="username" id="username" required="" placeholder="Nhập tên tài khoản">
                                </div>

                                <div class="form-group">
                                    <label class="float-left" for="password">Mật khẩu</label>
                                    <input class="form-control" type="password" name="password" required="" id="password" placeholder="Nhập mật khẩu">
                                </div>

                                <div class="form-group">
                                    <label class="float-left" for="password">Nhập lại mật khẩu</label>
                                    <input class="form-control" type="password" required="" id="password" placeholder="Nhập lại mật khẩu">
                                </div>

                                <div class="form-group mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox-signin">
                                        <label class="custom-control-label" for="checkbox-signin">Nhớ mật khẩu</label>
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block" type="submit"> Đăng ký </button>
                                    <a class="btn btn-secondary btn-block" href="{{route('getLogin')}}"> Đăng nhập </a>
                                </div>

                                <input type="hidden" name="isActive" value="0">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
</body>
</html>