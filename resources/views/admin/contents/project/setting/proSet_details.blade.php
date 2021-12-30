
@extends('admin.layouts.master')
@section('title', 'Chi tiết dự án')

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
                    <a href="{{route('getSettingDetails', ['proID' => $pro->proID])}}" class="nav-link active" aria-current="page">
                        Chi tiết
                    </a>
                </li>
                <li>
                    <a href="{{route('getSettingMember', ['proID' => $pro->proID])}}" class="nav-link link-dark">
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
                <li class="breadcrumb-item"><a href="#">Dự án</a></li>
                <li class="breadcrumb-item"><a href="#">{{$pro->proName}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thiết lập</li>
                </ol>
            </nav>
            <div class="col-sm-6">
                <h2>Chi tiết</h2>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right" style="float: right;">
                    <span class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="proDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
                        <ul class="dropdown-menu" aria-labelledby="proDropdown">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delProject">
                                    Xóa dự án
                                </a>
                            </li>
                        </ul>
                    </span>
                </ol>
            </div>

            <form action="{{route('quanlyduan.update', ['quanlyduan' => $pro->proID])}}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="proName" class="form-label">Tên dự án</label>
                    <input type="text" class="form-control" id="proName" name="proName" value="{{$pro->proName}}">
                </div>
                <div class="mb-3">
                    <label for="proKey" class="form-label">Key</label>
                    <input type="text" class="form-control" id="proKey" name="proKey" value="{{$pro->proKey}}">
                </div>
                <div class="mb-3">
                    <label for="proDesc" class="form-label">Mô tả dự án</label>
                    <textarea class="form-control" name="proDesc" id="proDesc" cols="30" rows="3">{{$pro->proDesc}}</textarea>
                </div>
                <div class="mb-3">
                    <label for="created_by" class="form-label">Project Manager</label>
                    <h5>
                        @foreach ($pro->member as $mem)
                            @if ($mem->roleID == 1)
                                <span class="badge bg-info">
                                    {{$mem->user->username}}
                                </span>
                            @endif
                        @endforeach
                    </h5>
                </div>
                <div class="mb-3">
                    <label for="memID" class="form-label">Default Assignee</label>
                    <h5>
                        <span class="badge bg-secondary bg-sm">
                            {{isset($pro->default_assignee) ? $pro->getDefaultAssignee($pro->default_assignee) : 'Unassignee'}}
                        </span>
                    </h5>
                </div>
                <div class="mb-3">
                    <label for="startDate" class="form-label">Ngày bắt đầu</label>
                    <input type="date" class="form-control" id="startDate" name="startDate" value="{{$pro->date->startDate}}">
                </div>
                <button type="submit" class="btn btn-primary">Lưu thông tin</button>
            </form>
        </div>
    </div>
</div>
{{-- Modal xóa dự án --}}
<div class="modal fade" id="delProject" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Xác nhận xóa dự án</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('delProject', ['proID' => $pro->proID])}}" method="post">
                @csrf
                @method('put')
                <div class="modal-body">
                    <strong>Dự án sẽ được di chuyển vào thùng rác và bạn có thể phục hồi nó trong 10 ngày</strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                    <button type="submit" class="btn btn-danger">Đồng ý</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection