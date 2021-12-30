@extends('admin.layouts.master')

@section('title', 'Quản lý tài khoản')

@section('sidebar-content')
<div class="row">

    <div class="col-3">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                Admin
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{route('quanlytaikhoan.index')}}" class="nav-link active" aria-current="page">
                        Quản lý người dùng
                    </a>
                </li>
            </ul>
            <hr>
        </div>
    </div>

    <div class="col-8">
        <div class="row mb-2">
            <nav style="--bs-breadcrumb-divider: '>'; padding-top: 10px;" aria-label="breadcrumb" >
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tài khoản</li>
                </ol>
            </nav>

            <div class="col-sm-6">
                <h2>Tài khoản</h2>
                <form action="{{route('filterAccount')}}" style="width: 50%" method="post" id="formFilterAccount">
                    @csrf
                    <strong>Lọc theo:</strong>
                    <select class="form-control" name="isActive" id="filterValue" onchange="filterAccount()">
                        <option value="">...</option>
                        <option @if ($isActive == 0)
                        selected
                        @endif value="0">Chưa kích hoạt</option>
                        <option @if ($isActive == 2)
                        selected
                        @endif value="2">Vô hiệu hóa</option>
                    </select>
                </form>
                {{--  Script lọc tài khoản  --}}
                <script>
                    function filterAccount(){
                        var form = document.getElementById('formFilterAccount');
                        form.submit();
                    }
                </script>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right" style="float: right;">
                    <a class="btn btn-success btn-sm" href="" data-bs-toggle="modal" data-bs-target="#addAccount">
                        Thêm tài khoản mới
                    </a>
                </ol>
            </div>

            <!-- Modal thêm tài khoản -->
            <div class="modal fade" id="addAccount" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Thêm tài khoản</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{route('postRegister')}}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="float-left" for="username">Tên đăng nhập</label>
                                    <input class="form-control" type="text" name="username" id="username" required="" placeholder="Nhập tên tài khoản">
                                </div>

                                <div class="form-group">
                                    <label class="float-left" for="password">Mật khẩu</label>
                                    <input class="form-control" type="password" name="password" required="" id="password" placeholder="Nhập mật khẩu">
                                </div>

                                <div class="form-group">
                                    <label class="float-left" for="re-pass">Nhập lại mật khẩu</label>
                                    <input class="form-control" type="password" name="re-pass" required="" id="re-pass" placeholder="Nhập lại mật khẩu">
                                </div>

                                <input type="hidden" name="isActive" value="1">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Thêm tài khoản mới</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Tài khoản</th>
                        <th>Trạng thái</th>
                        <th>Tùy chỉnh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user as $user)
                        <tr>
                            <td>
                                {{$i++}}
                            </td>
                            <td>
                                {{$user->information->name}}
                            </td>
                            <td>
                                {{$user->username}}
                            </td>
                            <td>
                                @if($user->isActive == 1)
                                    <span class="badge bg-success">Đã kích hoạt</span>
                                @endif
                                @if($user->isActive == 0)
                                    <form action="{{route('activeUser', ['userID' => $user->userID])}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn" type="submit">
                                            <span class="badge bg-danger">Chưa kích hoạt</span>
                                        </button>
                                    </form>
                                @endif
                                @if($user->isActive == 2)
                                    <form action="{{route('activeUser', ['userID' => $user->userID])}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn" type="submit">
                                            <span class="badge bg-secondary">Vô hiệu hóa</span>
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td>
                                <span class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="proDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
                                    <ul class="dropdown-menu" aria-labelledby="proDropdown">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#info{{$user->userID}}">
                                                Thông tin tài khoản
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            @if ($user->userID != 1)
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete{{$user->userID}}">
                                                    Khóa tài khoản
                                                </a>
                                            @endif
                                        </li>
                                    </ul>
                                </span>
                            </td>
                        </tr>
                        {{-- Modal thông tin cá nhân --}}
                        <div class="modal fade" id="info{{$user->userID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Thông tin cá nhân</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{route('quanlytaikhoan.update', ['quanlytaikhoan' => $user->userID])}}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="modal-body">

                                            <div class="form-group">
                                                <label for="username">Tài khoản</label>
                                                <input class="form-control" type="text" name="username" id="username" value="{{$user->username}}" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="name">Họ tên</label>
                                                <input class="form-control" type="text" name="name" id="name" value="{{$user->information->name}}">
                                            </div>

                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input class="form-control" type="text" name="email" id="email" value="{{$user->information->email}}">
                                            </div>

                                            <div class="form-group">
                                                <label for="address">Địa chỉ</label>
                                                <input class="form-control" type="text" name="address" id="address" value="{{$user->information->address}}">
                                            </div>

                                            <div class="form-group">
                                                <label for="phone">Số điện thoại</label>
                                                <input class="form-control" type="text" name="phone" id="phone" value="{{$user->information->phone}}">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Modal khóa tài khoản --}}
                        <div class="modal fade" id="delete{{$user->userID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Xác nhận khóa tài khoản</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{route('disable', ['userID' => $user->userID])}}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="modal-body">
                                            <p>Bạn có chắc chắn khóa tài khoản <strong>{{$user->username}}</strong> không?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-danger">Khóa tài khoản</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
