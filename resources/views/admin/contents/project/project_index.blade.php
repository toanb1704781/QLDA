@extends('admin.layouts.master')

@section('title', 'Dự án')

@section('content')
<div class="card" style="padding: 20px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Dự án đang tham gia</h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right" style="float: right;">
                        <a class="btn btn-success btn-sm" href="" data-bs-toggle="modal" data-bs-target="#addPro">
                            <i class="fas fa-plus"></i>
                            Thêm dự án mới
                        </a>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="card-body p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tên dự án</th>
                    <th>Key</th>
                    <th>Project Manager</th>
                    <th>Ngày bắt đầu</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($project as $pro)
                    <tr>
                        <td>
                            <a class="btn" href="{{route('quanlyduan.show', ['quanlyduan' => $pro->proID])}}">
                                {{$pro->proName}}
                            </a>
                        </td>
                        <td>
                            {{$pro->proKey}}
                        </td>
                        <td>
                            @foreach ($pro->member as $mem)
                                @if ($mem->roleID == 1)
                                    <span class="badge bg-primary">{{$mem->user->username}}</span>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            {{$pro->date->formatDate($pro->date->startDate)}}
                        </td>
                        <td>
                            @if ($userID == 1 || $userID == $pro->created_by)
                                <span class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="proDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Xem thêm
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="proDropdown">
                                        <li>
                                            <a class="dropdown-item" href="{{route('getSettingDetails', ['proID' => $pro->proID])}}">
                                                Tùy chỉnh
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delProject{{$pro->proID}}">
                                                Hủy dự án
                                            </a>
                                        </li>
                                    </ul>
                                </span>
                            @endif
                        </td>
                    </tr>
                    {{-- Modal xóa dự án --}}
                    <div class="modal fade" id="delProject{{$pro->proID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
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
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Modal thêm dự án mới --}}
    <div class="modal fade" id="addPro" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Thêm dự án mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('quanlyduan.store')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="proName">Tên dự án</label>
                            <input class="form-control" type="text" name="proName" id="proName" placeholder="Nhập tên dự án">
                        </div>

                        <div class="form-group">
                            <label for="proKey">Key</label>
                            <input class="form-control" type="text" name="proKey" id="proKey" placeholder="Nhập key dự án...">
                        </div>

                        <script>
                            function myFunction() {
                                let str = document.getElementById('proName').value;
                                var key = str.charAt(0); 
                                for(var i = 1; i< str.length; i++){
                                    if(str.charAt(i) == " "){
                                    key = key + str.charAt(i+1);
                                }
                            }
                            document.getElementById("proKey").value = key.toUpperCase();
                            }
                        </script>

                        <div class="form-group">
                            <label for="proDesc">Mô tả dự án</label>
                            <textarea class="form-control" type="text" name="proDesc" id="proDesc" cols="30" rows="3" placeholder="Mô tả dự án"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="startDate">Ngày bắt đầu</label>
                            <input class="form-control" type="date" name="startDate" id="startDate" value="{{App\Models\Date::getDateNow()}}">
                        </div>
                    </div>

                    <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Thêm dự án</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection