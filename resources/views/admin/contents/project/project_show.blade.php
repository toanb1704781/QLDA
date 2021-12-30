@extends('admin.layouts.master')
@section('title', 'Công việc dự án')

@section('stylePage')
    <link href="{{asset('css/main_style/project_show.css')}}" rel="stylesheet">
@endsection

@section('sidebar-content')
<div class="row">
    <div class="col-2">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light">
            <a href="{{route('quanlyduan.show', ['quanlyduan' => $project->proID])}}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <strong>
                    {{$project->proName}}
                </strong>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active" aria-current="page">
                        Công việc
                    </a>
                </li>
                @if ($project->isPM($project->proID) || session()->get('userID') == 1)
                    <hr>
                    <li>
                        <a href="{{route('getSettingDetails', ['proID' => $project->proID])}}" class="nav-link link-dark" aria-current="page">
                            Thiết lập
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="col-9 mb-0">
        <div class="row" style="margin-right: -120px">
            <nav style="--bs-breadcrumb-divider: '>'; padding-top: 10px;" aria-label="breadcrumb" >
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-decoration-none text-black-50" href="{{route('quanlyduan.index')}}">
                            Dự án
                        </a
                    </li>
                    <li class="breadcrumb-item">
                        <a class="text-decoration-none text-black-50" href="{{route('quanlyduan.show', ['quanlyduan' => $project->proID])}}">
                            {{$project->proName}}
                        </a>
                    </li>
                </ol>
            </nav>

            <div class="col-sm-6">
                <h2>Công việc dự án</h2>
                <form action="{{route('filterIssue', ['proID' => $project->proID])}}" style="width: 50%" method="get" id="formFilterIssue">
                    <strong>Lọc theo:</strong>
                    <select class="form-control" name="filterIssue" id="filterValue" onchange="filter()">
                        <option value="6" selected>Tất cả công việc</option>
                        <option value="7" @if ($value == 7)
                        selected
                        @endif>Công việc của tôi</option>
                        @foreach ($workflow as $workflow)
                            <option @if ($workflow->workflowID == $value)
                            selected
                            @endif value="{{$workflow->workflowID}}">Trạng thái {{$workflow->workflow}}</option>
                        @endforeach
                    </select>
                </form>
                <script>
                    function filter(){
                        var formFilter = document.getElementById('formFilterIssue');
                        formFilter.submit();
                    }
                </script>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right" style="float: right;">
                    <a class="btn btn-success btn-sm" href="" data-bs-toggle="modal" data-bs-target="#addIssue">
                        Thêm công việc
                    </a>
                </ol>
            </div>

            @if ($issue->isNotEmpty())
                <div class="row">
                    <div class="col-6">
                        <div class="overflow-auto p-3 mb-3 mb-md-0 me-md-3 bg-light" style="width: 100%; max-height: 395px">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Công việc</th>
                                        <th>Thực hiện</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($issue as $issue)
                                        <tr>
                                            <td>
                                                <form action="{{route('quanlyduan.show', ['quanlyduan' => $issue->proID])}}" method="GET">
                                                    @if ($issue->issue_type->typeID == 3)
                                                        <span class="badge bg-info">{{$issue->issue_type->issue_type}}</span>
                                                    @endif
                                                    @if ($issue->issue_type->typeID == 4)
                                                        <span class="badge bg-danger">{{$issue->issue_type->issue_type}}</span>
                                                    @endif
                                                    <input type="hidden" name="selectedIssue" value="{{$issue->issueID}}">
                                                    <button class="btn">
                                                        <span class="text-black-50">
                                                            {{$issue->issueKey}}
                                                        </span>
                                                        <span>
                                                            {{Str::of($issue->summary)->limit(30)}}
                                                        </span>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                {{$issue->member->user->username ?? null}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (isset($issueDetail))
                        <div class="col-6" style="padding-left: 0px">
                            <nav style="--bs-breadcrumb-divider: '>'; padding-top: 10px;" aria-label="breadcrumb" >
                                <button class="btn float-end">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <a class="btn float-end" href="{{route('issue.show', ['issueID' => $issueDetail->issueID])}}">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <a class="btn float-end" data-bs-toggle="modal" data-bs-target="#pro_shareIssue">
                                    <i class="fas fa-share-alt"></i>
                                </a>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page">
                                        @if ($issueDetail->issue_type->typeID == 3)
                                            <span class="badge bg-info">{{$issueDetail->issue_type->issue_type}}</span>
                                        @endif
                                        @if ($issueDetail->issue_type->typeID == 4)
                                            <span class="badge bg-danger">{{$issueDetail->issue_type->issue_type}}</span>
                                        @endif
                                        <a class="text-decoration-none text-black-50" href="{{route('issue.show', ['issueID' => $issueDetail->issueID])}}">
                                            {{$issueDetail->issueKey}}
                                        </a>
                                    </li>
                                </ol>
                            </nav>
                            <div class="container" style="padding: 0">
                                <div class="row">
                                    <div class="overflow-auto p-2 mb-3 mb-md-0 me-md-3" style="max-height: 349px">
                                        {{-- Tiêu đề --}}
                                        <div class="">
                                            <h3>
                                                {{$issueDetail->summary}}
                                            </h3>
                                        </div>
                                        <div class="">
                                            <label for="files" class="btn btn-light btn-sm" style="background-color: rgb(230 230 230)">
                                                <i class="fas fa-paperclip"></i>
                                                Đính kèm
                                            </label>
                                            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editIssue" style="background-color: rgb(230 230 230)">
                                                <i class="fas fa-edit"></i>
                                                Chỉnh sửa
                                            </button>
                                            <form id="formUploads" action="{{route('issue.uploads', ['issueID' => $issueDetail->issueID])}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input id="files" type="file" style="display: none" name="uploadFile[]" multiple onchange="uploads()">
                                            </form>
                                            <script>
                                                function uploads(){
                                                    var form = document.getElementById('formUploads');
                                                    form.submit();
                                                }
                                            </script>
                                        </div>
                                        {{-- Main Button --}}
                                        <div class="main-button">
                                            <form action="{{route('workflow', ['issueID' => $issueDetail->issueID])}}" method="POST" id="pro_formWorkflow{{$issueDetail->issueID}}">
                                                @csrf
                                                @method('PUT')
                                                <select class="form-control" name="workflowID" id="" onchange="pro_udpWorkflow{{$issueDetail->issueID}}()">
                                                    @if ($issueDetail->workflow->workflowID == 1)
                                                        <option value="1" selected>Open</option>
                                                        <option value="2">In Progress</option>
                                                        <option value="3">Resolved</option>
                                                        <option value="5">Closed</option>
                                                    @endif
                                                    @if ($issueDetail->workflow->workflowID == 2)
                                                        <option value="2" selected>In Progress</option>
                                                        <option value="1">Open</option>
                                                        <option value="3">Resolved</option>
                                                        <option value="5">Closed</option>
                                                    @endif
                                                    @if ($issueDetail->workflow->workflowID == 3)
                                                        <option value="3" selected>Resolved</option>
                                                        <option value="4">Reopen</option>
                                                        <option value="5">Closed</option>
                                                    @endif
                                                    @if ($issueDetail->workflow->workflowID == 4)
                                                        <option value="4" selected>Reopen</option>
                                                        <option value="2">In Progress</option>
                                                        <option value="3">Resolved</option>
                                                        <option value="5">Closed</option>
                                                    @endif
                                                    @if ($issueDetail->workflow->workflowID == 5)
                                                        <option value="5" selected>Closed</option>
                                                        <option value="4">Reopen</option>
                                                    @endif
                                                </select>
                                            </form>
                                            <script>
                                                function pro_udpWorkflow@php echo json_encode($issueDetail->issueID);@endphp(){
                                                    var pro_formWorkflow = '@php echo 'pro_formWorkflow' .json_encode($issueDetail->issueID);@endphp';
                                                    var pro_formWorkflow@php echo json_encode($issueDetail->issueID);@endphp = document.getElementById(pro_formWorkflow);
                                                    pro_formWorkflow@php echo json_encode($issueDetail->issueID);@endphp.submit();
                                                }
                                            </script>
                                        </div>
                                        {{-- Chi tiết --}}
                                        <div class="mt-3 me-2 mb-3">
                                            <h6>Mô tả</h6>
                                            <p class="issue-desc">
                                                {{$issueDetail->issueDesc}}
                                            </p>
                                            @if(!$upload_file->isEmpty())
                                                <h6>File đính kèm</h6>
                                                @foreach ($upload_file as $file)
                                                    <a class="" href="{{asset('storage/uploads/' .$file->fileName)}}" download>
                                                        {{$file->fileName}}
                                                    </a>
                                                    <button class="btn" data-bs-toggle="modal" data-bs-target="#desFile{{$file->fileID}}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    |
                                                    {{--  Modal xóa file  --}}
                                                    <div class="modal fade" id="desFile{{$file->fileID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Xác nhận xóa bình luận</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{route('file.destroy', ['fileID' => $file->fileID])}}" method="POST">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <div class="modal-body">
                                                                        Bạn có muốn xóa file này?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                        <button type="submit" class="btn btn-danger">Đồng ý</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="mt-5">
                                            <div>
                                                <table style="width:100%">
                                                    <tr>
                                                        <th class="pb-4">
                                                            Người thực hiện
                                                        </th>
                                                        <td class="pb-4">
                                                            {{$issueDetail->member->user->username ?? null}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="pb-4">
                                                            Người báo cáo
                                                        </th>
                                                        <td class="pb-4">
                                                            {{$issueDetail->getReporter($issue->reporter)}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="pb-4">
                                                            Độ ưu tiên
                                                        </th>
                                                        <td class="pb-4">
                                                            {{$issueDetail->priority->priority}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="pb-4">
                                                            Ước tính ban đầu
                                                        </th>
                                                        <td class="pb-4">
                                                            <span class="badge bg-secondary">
                                                                {{$issueDetail->original_estimate}}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="pb-4">
                                                            OLA
                                                        </th>
                                                        <td class="pb-4">
                                                            <span class="badge bg-secondary">
                                                                @if ($issueDetail->equalDate($issueDetail->ola, $issueDetail->created_at))
                                                                    {{$issueDetail->getOLA($issueDetail->issueID, carbon\Carbon::now()->addHour(7) )}}p
                                                                @else
                                                                    {{$issueDetail->getOLA($issueDetail->issueID, carbon\carbon::create($issueDetail->ola) )}}p
                                                                @endif
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="pb-4">
                                                            SLA
                                                        </th>
                                                        <td class="pb-4">
                                                            <span class="badge bg-secondary">
                                                                @if ($issue->sla == null)
                                                                        480h
                                                                @else
                                                                    @if ($issueDetail->workflowID != 5)
                                                                        {{$issueDetail->getSLA($issueDetail->issueID, carbon\Carbon::now()->addHour(7) )}}h
                                                                    @else
                                                                        {{$issueDetail->getSLA($issueDetail->issueID, carbon\carbon::create($issueDetail->sla) )}}h
                                                                    @endif
                                                                @endif
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="pb-4">
                                                            Thời gian bắt đầu
                                                        </th>
                                                        <td class="pb-4">
                                                            {{$issueDetail->date->formatDate($issueDetail->date->startDate)}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Dự kiến hoàn thành
                                                        </th>
                                                        <td>
                                                            {{$issueDetail->date->formatDate($issueDetail->date->endDate)}}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <h6 class="mt-5">Hoạt động</h6>
                                        <button style="background-color: rgb(230, 230, 230)" class="btn btn-light btn-sm">Tất cả</button>
                                        <button class="btn btn-secondary btn-sm">Bình luận</button>
                                        <button style="background-color: rgb(230, 230, 230)" class="btn btn-light btn-sm">Lịch sử</button>
                                        <div class="mb-3 mt-3">
                                            <form id="formPostCmt" action="{{route('issue.comment', ['issueID' => $issueDetail->issueID])}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input id="inputCmt" type="text" class="form-control" onfocus="inputComment()" placeholder="Nhập bình luận...">
                                                <div class="mb-1" id="option_comment" style="display: none">
                                                    <input type="file" name="cmt_files[]" multiple>
                                                </div>
                                                <textarea id="txtArea" style="display: none" class="form-control" name="comment" id="" col="10" rows="3" placeholder="Nhập bình luận..."></textarea>
                                            </form>
                                            <div class="float-end mt-1" style="display:none" id="btn_inputComment">
                                                <button class="btn btn-light" onclick="hiddenInputCmt()">Đóng</button>
                                                <button form="formPostCmt" type="submit" class="btn btn-primary ">Gửi</button>
                                            </div>
                                            <script>
                                                function inputComment(){
                                                    var txt = document.getElementById('txtArea').style.display = "block";
                                                    var option_comment = document.getElementById('option_comment').style.display = "block";
                                                    var btn_inputComment = document.getElementById('btn_inputComment').style.display = "block";
                                                    var input = document.getElementById('inputCmt').style.display = "none";
                                                }
                                                function hiddenInputCmt(){
                                                    var txt = document.getElementById('txtArea').style.display = "none";
                                                    var btn_inputComment = document.getElementById('btn_inputComment').style.display = "none";
                                                    var option_comment = document.getElementById('option_comment').style.display = "none";
                                                    var input = document.getElementById('inputCmt').style.display = "block";
                                                }
                                            </script>
                                        </div>
                                        @foreach ($comment->sortByDesc('created_at') as $cmt)
                                            <div class="">
                                                <strong>
                                                    {{$cmt->member->user->username}}
                                                </strong> 
                                                {{$cmt->getTime($cmt->created_at)}}
                                                <p class="issue-desc mb-0">
                                                    {{$cmt->comment}}
                                                </p>
                                                @foreach ($cmt->upload_file as $cmt_file)
                                                    <a class="" href="{{asset('storage/uploads/' .$cmt_file->fileName)}}" download>
                                                        {{$cmt_file->fileName}}
                                                    </a>
                                                    <a class="btn" data-bs-toggle="modal" data-bs-target="#desFile{{$cmt_file->fileID}}">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                @endforeach
                                                <div class="mb-3">
                                                    <a class="text-decoration-none text-secondary me-2" href="" data-bs-toggle="modal" data-bs-target="#repCmt{{$cmt->cmtID}}">Trả lời</a> 
                                                    <a class="text-decoration-none text-secondary me-2" href="" data-bs-toggle="modal" data-bs-target="#editCmt{{$cmt->cmtID}}">Sửa</a> 
                                                    <a class="text-decoration-none text-secondary" href="" data-bs-toggle="modal" data-bs-target="#deleteCmt{{$cmt->cmtID}}">Xóa</a> 
                                                </div>
                                            </div>

                                            @foreach ($cmt->getRepCmt($cmt->cmtID) as $repCmt)
                                                <div class="ms-5">
                                                    <strong>
                                                        {{$repCmt->member->user->username}}
                                                    </strong> 
                                                    {{$repCmt->getTime($repCmt->created_at)}}
                                                    <p class="issue-desc mb-0">
                                                        {{$repCmt->comment}}
                                                    </p>
                                                    @foreach ($repCmt->upload_file as $cmt_file)
                                                        <a class="" href="{{asset('storage/uploads/' .$cmt_file->fileName)}}" download>
                                                            {{$cmt_file->fileName}}
                                                        </a>
                                                        <a class="btn" data-bs-toggle="modal" data-bs-target="#desFile{{$cmt_file->fileID}}">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    @endforeach
                                                    <div class="mb-3">
                                                        <a class="text-decoration-none text-secondary me-2" href="" data-bs-toggle="modal" data-bs-target="#editCmt{{$repCmt->cmtID}}">Sửa</a> 
                                                        <a class="text-decoration-none text-secondary" href="" data-bs-toggle="modal" data-bs-target="#deleteCmt{{$repCmt->cmtID}}">Xóa</a> 
                                                    </div>
                                                </div>

                                                {{--  Modal chỉnh sửa bình luận  --}}
                                                <div class="modal fade" id="editCmt{{$repCmt->cmtID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Cập nhật bình luận</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{route('comment.update', ['cmtID' => $repCmt->cmtID])}}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <input class="form-control" type="file" name="cmt_files[]" multiple> <br>
                                                                    <textarea class="form-control" name="comment" id="" cols="30" rows="3">{{$repCmt->comment}}</textarea>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{--  Modal bình luận  --}}
                                                <div class="modal fade" id="deleteCmt{{$repCmt->cmtID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Xác nhận xóa bình luận</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{route('comment.destroy', ['cmtID' => $repCmt->cmtID])}}" method="POST">
                                                                @csrf
                                                                @method('delete')
                                                                <div class="modal-body">
                                                                    Bạn có muốn xóa bình luận này?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                    <button type="submit" class="btn btn-danger">Đồng ý</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            {{--  Modal trả lời bình luận  --}}
                                            <div class="modal fade" id="repCmt{{$cmt->cmtID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Trả lời bình luận</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{route('repComment', ['cmtID' => $cmt->cmtID])}}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <input class="form-control" type="file" name="cmt_files[]" multiple> <br>
                                                                <textarea class="form-control" name="comment" id="" cols="30" rows="3"></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-primary">Gửi</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            {{--  Modal chỉnh sửa bình luận  --}}
                                            <div class="modal fade" id="editCmt{{$cmt->cmtID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Cập nhật bình luận</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{route('comment.update', ['cmtID' => $cmt->cmtID])}}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <input class="form-control" type="file" name="cmt_files[]" multiple> <br>
                                                                <textarea class="form-control" name="comment" id="" cols="30" rows="3">{{$cmt->comment}}</textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-primary">Lưu</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            {{--  Modal bình luận  --}}
                                            <div class="modal fade" id="deleteCmt{{$cmt->cmtID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Xác nhận xóa bình luận</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{route('comment.destroy', ['cmtID' => $cmt->cmtID])}}" method="POST">
                                                            @csrf
                                                            @method('delete')
                                                            <div class="modal-body">
                                                                Bạn có muốn xóa bình luận này?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-danger">Đồng ý</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--  Modal chỉnh sửa issue  --}}
                        <div class="modal fade" id="editIssue" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Cập nhật Issue</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{route('issue.update', ['issueID' => $issueDetail->issueID])}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">

                                        <div class="form-group">
                                            <label for="typeID">Loại công việc</label>
                                            <select class="form-control" name="typeID" id="typeID">
                                                @foreach ($issue_type as $type)
                                                    <option @if ($type->typeID == $issue->typeID)
                                                    selected
                                                    @endif value="{{$type->typeID}}">{{$type->issue_type}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="summary">Công việc</label>
                                            <input class="form-control" type="text" name="summary" id="summary" value="{{$issueDetail->summary}}">
                                        </div>

                                        <div class="form-group">
                                            <label for="issueDesc">Mô tả công việc</label>
                                            <textarea class="form-control" type="text" name="issueDesc" id="issueDesc" cols="30" rows="3">{{$issueDetail->issueDesc}}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="memID">Người thực hiện</label>
                                            <select class="form-control" name="memID" id="memID">
                                                @foreach ($member as $mem)
                                                    <option @if ($mem->memID == $issue->memID)
                                                    selected
                                                    @endif value="{{$mem->memID}}">{{$mem->user->username}} - {{$mem->role->role}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="reporter">Người báo cáo</label>
                                            <select class="form-control" name="reporter" id="reporter">
                                                @foreach ($member as $mem)
                                                    <option @if ($issueDetail->reporter == $mem->memID)
                                                    selected
                                                    @endif value="{{$mem->memID}}">{{$mem->user->username}} - {{$mem->role->role}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="priorityID">Độ ưu tiên</label>
                                            <select class="form-control" name="priorityID" id="priorityID">
                                                @foreach ($priority as $priority_issue)
                                                    <option @if ($priority_issue->priorityID == $issue->priorityID)
                                                    selected
                                                    @endif value="{{$priority_issue->priorityID}}">{{$priority_issue->priority}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="startDate">Từ ngày</label>
                                            <input class="form-control" type="date" name="startDate" id="startDate" value="{{$issueDetail->date->startDate}}" >
                                        </div>

                                        <div class="form-group">
                                            <label for="endDate">Đến ngày</label>
                                            <input class="form-control" type="date" name="endDate" id="endDate" value="{{$issueDetail->date->endDate}}">
                                        </div>

                                        <div class="form-group">
                                            <label for="original_estimate">Ước tính thời gian làm</label>
                                            <input class="form-control" type="text" name="original_estimate" id="original_estimate" min=0 value="{{$issueDetail->original_estimate}}">
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{--  Modal chia sẻ issue  --}}
                        <div class="modal fade" id="pro_shareIssue" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Chia sẻ Issue</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{route('postShareIssue', ['issueID' => $issueDetail->issueID])}}" method="POST">
                                        @csrf
                                        <div class="modal-body">

                                            <div class="form-group">
                                                <label for="userID_share">Tài khoản</label>
                                                <select class="form-select" name="userID_share[]" id="userID_share" multiple >
                                                    @foreach ($allUser as $user)
                                                        <option value="{{$user->userID}}">{{$user->username}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Chia sẻ</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal thêm công việc --}}
<div class="modal fade" id="addIssue" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Thêm công việc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('issue.store', ['proID' => $project->proID])}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="typeID">Loại công việc</label>
                        <select class="form-control" name="typeID" id="typeID">
                            <option value="0">...</option>
                            @foreach (App\Models\Issue_type::allIssueType() as $type)
                                <option value="{{$type->typeID}}">{{$type->issue_type}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="summary">Công việc</label>
                        <input class="form-control" type="text" name="summary" id="summary" placeholder="Nhập tóm lược công việc..." required>
                    </div>

                    <div class="form-group">
                        <label for="issueDesc">Mô tả công việc</label>
                        <textarea class="form-control" type="text" name="issueDesc" id="issueDesc" cols="30" rows="3" placeholder="Mô tả công việc"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="memID">Người thực hiện</label>
                        <select class="form-control" name="memID" id="memID" required>
                            @if (isset($project->default_assignee))
                                <option value="{{$project->default_assignee}}">
                                    {{$project->getDefaultAssignee($project->default_assignee)}} - Default Assignee
                                </option>
                            @endif
                            @foreach ($member as $mem)
                                <option value="{{$mem->memID}}">{{$mem->user->username}} - {{$mem->role->role}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="reporter">Người báo cáo</label>
                        <select class="form-control" name="reporter" id="reporter">
                            @foreach ($member as $mem)
                                <option @if (session()->get('userID') == $mem->memID)
                                selected
                                @endif value="{{$mem->memID}}">{{$mem->user->username}} - {{$mem->role->role}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="priorityID">Độ ưu tiên</label>
                        <select class="form-control" name="priorityID" id="priorityID">
                            @foreach ($priority as $priority)
                                <option @if ($priority->priorityID == 3)
                                selected
                                @endif value="{{$priority->priorityID}}">{{$priority->priority}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="startDate">Từ ngày</label>
                        <input class="form-control" type="date" name="startDate" id="startDate" value="{{App\Models\Date::getDateNow()}}" >
                    </div>

                    <div class="form-group">
                        <label for="endDate">Đến ngày</label>
                        <input class="form-control" type="date" name="endDate" id="endDate" required>
                    </div>

                    <div class="form-group">
                        <label for="original_estimate">Ước tính thời gian làm</label>
                        <input class="form-control" type="text" name="original_estimate" id="original_estimate" required placeholder="Ví dụ: 5h (5 giờ), 1d (1 ngày)">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Thêm công việc</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection