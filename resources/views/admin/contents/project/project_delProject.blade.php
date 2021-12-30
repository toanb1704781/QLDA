@extends('admin.layouts.master')

@section('title', 'Dự án đã hủy')

@section('content')
<div class="card" style="padding: 20px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Dự án đã hủy</h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right" style="float: right;"></ol>
                </div>
            </div>
        </div>
    </section>

    <div class="card-body p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên dự án</th>
                    <th>Ngày bắt đầu</th>
                    <th>Người tạo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {{-- Trang quản lý dự án admin --}}
                @if ($userID == 1)
                    @foreach ($project as $pro)
                        <tr>
                            <td>
                                {{$i++}}
                            </td>
                            <td>
                                <a class="btn" href="{{route('quanlyduan.show', ['quanlyduan' => $pro->proID])}}">
                                    {{$pro->proName}}
                                </a>
                            </td>
                            <td>
                                {{$pro->date->startDate}}
                            </td>
                            <td>
                                {{$pro->user->username}}
                            </td>
                            <td>
                                <span class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="proDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Xem thêm
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="proDropdown">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#resProject{{$pro->proID}}">
                                                Khôi phục
                                            </a>
                                        </li>
                                    </ul>
                                </span>
                            </td>
                        </tr>
                        {{-- Modal khôi phục dự án --}}
                        <div class="modal fade" id="resProject{{$pro->proID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">XÁC NHẬN</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{route('restoreProject', ['proID' => $pro->proID])}}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="modal-body">
                                            <strong>Bạn chắc chắn khôi phục dự án này?</strong>
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
                @else
                    {{-- Trang quản lý dự án user --}}
                    @foreach ($project as $mem)
                        @if ($mem->roleID != 0 && $mem->project->statusID == 3)
                            <tr>
                                <td>
                                    {{$i++}}
                                </td>
                                <td>
                                    <a class="btn" href="{{route('quanlyduan.show', ['quanlyduan' => $mem->proID])}}">
                                        {{$mem->project->proName}}
                                    </a>
                                </td>
                                <td>
                                    {{$mem->project->date->startDate}}
                                </td>
                                <td>
                                    {{$mem->project->user->username}}
                                </td>
                                <td>
                                    @if ($mem->project->created_by == $userID)
                                        <span class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="proDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Xem thêm
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="proDropdown">
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#resProject{{$mem->proID}}">
                                                        Khôi phục
                                                    </a>
                                                </li>
                                            </ul>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Modal khôi phục dự án --}}
                            <div class="modal fade" id="resProject{{$mem->proID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">XÁC NHẬN</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{route('restoreProject', ['proID' => $mem->proID])}}" method="post">
                                            @csrf
                                            @method('put')
                                            <div class="modal-body">
                                                <strong>Bạn chắc chắn khôi phục dự án này?</strong>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                                                <button type="submit" class="btn btn-danger">Đồng ý</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection