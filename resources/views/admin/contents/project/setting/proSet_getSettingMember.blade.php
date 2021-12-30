@extends('admin.layouts.master')
@section('title', 'Thành viên')

@section('sidebar-content')
<div class="row">

    <div class="col-3">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
            <a href="{{route('quanlyduan.show', ['quanlyduan' => $pro->proID])}}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <strong>
                    {{$pro->proName}}
                </strong>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{route('getSettingDetails', ['proID' => $pro->proID])}}" class="nav-link link-dark" aria-current="page">
                        Chi tiết
                    </a>
                </li>
                <li>
                    <a href="{{route('getSettingMember', ['proID' => $pro->proID])}}" class="nav-link active ">
                        Thành viên
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
                    <li class="breadcrumb-item">
                        <a href="#">Dự án</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">
                            {{$pro->proName}}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Thiết lập</li>
                </ol>
            </nav>

            <div class="col-sm-6">
                <h2>Thành viên</h2>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right" style="float: right;">
                    <a class="btn btn-success btn-sm" href="" data-bs-toggle="modal" data-bs-target="#addMember">
                        Thêm thành viên
                    </a>
                </ol>
            </div>

            {{-- Modal thêm thành viên --}}
            <div class="modal fade" id="addMember" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Thêm thành viên</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{route('postSettingMember')}}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="userID">Tài khoản thành viên</label>
                                    <select class="form-control" name="userID" id="userID">
                                        <option value="0">...</option>
                                        @foreach ($user as $row_user)
                                            <option value="{{$row_user->userID}}">{{$row_user->username}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="role">Vai trò trong dự án</label>
                                    <select class="form-control" name="roleID" id="role">
                                        <option value="0">...</option>
                                        @foreach ($role as $row_role)
                                            <option value="{{$row_role->roleID}}">{{$row_role->role}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <input type="hidden" name="proID" value="{{$pro->proID}}">

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Thêm thành viên</button>
                                </div>
                            </div>
                        </form>
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
                            <th>Vai trò trong dự án</th>
                            <th>Tùy chỉnh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($member as $member)
                        <tr>
                            <td>1</td>
                            <td>
                                {{$member->user->information->name}} 
                            </td>
                            <td>
                                {{$member->user->username}} <br>
                                @if ($member->memID == $member->project->default_assignee)
                                    <span class="badge bg-secondary">Default Assignee</span>
                                @endif
                            </td>
                            <td>
                                {{$member->role->role}}
                            </td>
                            <td>
                                <span class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="proDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
                                    <ul class="dropdown-menu" aria-labelledby="proDropdown">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#info{{$member->userID}}">
                                                Thông tin tài khoản
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#default{{$member->userID}}">
                                                Default Assignee
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            @if ($member->roleID != 1 || App\Models\User::checkAdmin())
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#removePro{{$member->userID}}">
                                                    Xóa khỏi dự án
                                                </a>
                                            @endif
                                        </li>
                                    </ul>
                                </span>
                            </td>
                        </tr>
                        {{-- Modal thông tin cá nhân --}}
                        <div class="modal fade" id="info{{$member->userID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Thông tin <strong>{{$member->user->information->name ?? $member->user->username}}</strong></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="username">Tài khoản</label>
                                            <input class="form-control" type="text" name="username" id="username" value="{{$member->user->username}}" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Họ tên</label>
                                            <input class="form-control" type="text" name="name" id="name" value="{{$member->user->information->name}}" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input class="form-control" type="text" name="email" id="email" value="{{$member->user->information->email}}" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="address">Địa chỉ</label>
                                            <input class="form-control" type="text" name="address" id="address" value="{{$member->user->information->address}}" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">Số điện thoại</label>
                                            <input class="form-control" type="text" name="phone" id="phone" value="{{$member->user->information->phone}}" disabled>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal set default assignee --}}
                        <div class="modal fade" id="default{{$member->userID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Xác nhận</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <form action="{{route('putSetDefaultAssignee', ['proID' => $member->proID, 'memID' => $member->memID])}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <p>
                                                Chọn <strong>{{$member->user->username}}</strong> là <strong>Default Assignee ?</strong>
                                            </p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                                            <button type="submit" class="btn btn-danger">Đồng ý</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{--  Modal xóa tài khoản khỏi dự án  --}}
                        <div class="modal fade" id="removePro{{$member->userID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Xác nhận khóa tài khoản</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{route('removePro', ['memID' => $member->memID, 'proID' => $member->proID])}}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="modal-body">
                                            <p>Bạn có chắc chắn xóa <strong>{{$member->user->information->name ?? $member->user->username}}</strong> ra khỏi dự án không?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                                            <button type="submit" class="btn btn-danger">Đồng ý</button>
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
</div>
@endsection
