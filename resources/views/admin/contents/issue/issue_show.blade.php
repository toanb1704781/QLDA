
@extends('admin.layouts.master')
@section('title', 'Chi tiết công việc')
@section('stylePage')
    <link href="{{asset('css/main_style/issue_show.css')}}" rel="stylesheet">
@endsection

@section('sidebar-content')
<div class="row">
    <div class="col-3">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
            @if ($project->isViewer($project->proID))
                <a href="" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                    <strong>
                        {{$issue->project->proName}}
                    </strong>
                </a>
            @else
            <a href="{{route('quanlyduan.show', ['quanlyduan' => $issue->proID])}}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <strong>
                    {{$issue->project->proName}}
                </strong>
            </a>
            @endif
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active" aria-current="page">
                        Công việc
                    </a>
                </li>
            </ul>
            <hr>
        </div>
    </div>

    <div class="col-8 ps-0 pe-0">
        <div class="container ps-0 pe-0">
            <nav style="--bs-breadcrumb-divider: '>'; padding-top: 10px;" aria-label="breadcrumb" >
                @if (!$project->isViewer($project->proID))
                    <a class="btn float-end">
                        <i class="fas fa-ellipsis-h"></i>
                    </a>
                    <a class="btn float-end" data-bs-toggle="modal" data-bs-target="#shareIssue">
                        <i class="fas fa-share-alt"></i>
                    </a>
                @endif
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-decoration-none text-black-50" href="{{route('quanlyduan.index')}}">
                            Dự án
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="text-decoration-none text-black-50"
                            @if (!$project->isViewer($project->proID)))
                                href="{{route('quanlyduan.show', ['quanlyduan' => $project->proID])}}"
                            @endif>
                            {{$issue->project->proName}}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        @if ($issue->issue_type->typeID == 3)
                            <span class="badge bg-info">{{$issue->issue_type->issue_type}}</span>
                        @endif
                        @if ($issue->issue_type->typeID == 4)
                            <span class="badge bg-danger">{{$issue->issue_type->issue_type}}</span>
                        @endif
                        {{$issue->issueKey}}
                    </li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-8 p-0">
                    <div class="overflow-auto mb-md-0 me-md-3" style="max-height: 495px">
                        {{-- Tiêu đề --}}
                        <div>
                            <h3>
                                {{$issue->summary}}
                            </h3>
                        </div>
                        @if (!$project->isViewer($project->proID))
                            <div class="">
                                <label for="files" class="btn btn-light btn-sm " style="background-color: rgb(230 230 230)">
                                    <i class="fas fa-paperclip"></i>
                                    Đính kèm
                                </label>
                                <button class="btn btn-light btn-sm"data-bs-toggle="modal" data-bs-target="#editIssue" style="background-color: rgb(230 230 230)">
                                    <i class="fas fa-edit"></i>
                                    Chỉnh sửa
                                </button>
                                <form id="formUploads" action="{{route('issue.uploads', ['issueID' => $issue->issueID])}}" method="post" enctype="multipart/form-data">
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
                        @endif

                        {{-- Chi tiết --}}
                        <div class="mt-4 me-2 mb-3">
                            <h6>Mô tả</h6>
                            <p class="issue-desc">
                                {{$issue->issueDesc}}
                            </p>

                            @if(!$upload_file->isEmpty())
                                <h6>File đính kèm</h6>
                                @foreach ($upload_file as $file)
                                    <a class="" href="{{asset('storage/uploads/' .$file->fileName)}}" download>
                                        {{$file->fileName}}
                                    </a>
                                    @if (!$project->isViewer($project->proID))
                                        <button class="btn" data-bs-toggle="modal" data-bs-target="#desFile{{$file->fileID}}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
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
                            <h6 class="mt-5">Hoạt động</h6>
                            <button style="background-color: rgb(230, 230, 230)" class="btn btn-light btn-sm">Tất cả</button>
                            <button class="btn btn-secondary btn-sm">Bình luận</button>
                            <button style="background-color: rgb(230, 230, 230)" class="btn btn-light btn-sm">Lịch sử</button>
                            <div class="mb-3 mt-3">
                                <form id="formPostCmt" action="{{route('issue.comment', ['issueID' => $issue->issueID])}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input id="inputCmt" type="text" class="form-control" onfocus="inputComment()" placeholder="Nhập bình luận...">
                                    <div class="mb-1" id="option_comment" style="display: none">
                                        <input id="cmt_files" style="width:50%; display:inline" class="form-control form-control-sm mb-1" type="file" name="cmt_files[]" multiple>
                                        <select id="selectMember" name="selectMember" class="form-control float-end" style="width:20%; display:none">
                                            <option value="">...</option>
                                            @foreach ($allMember as $allMember)
                                            <option value="{{$allMember->user->userID}}">{{$allMember->user->username}}</option>
                                            @endforeach
                                        </select>
                                        <label onclick="showSelectMember()" id="@" class="btn btn-light  float-end" style="background-color: rgb(230 230 230)">@</label>
                                    </div>
                                    <textarea id="txtArea" style="display: none" class="form-control" name="comment" id="" col="10" rows="3" placeholder="Nhập bình luận..."></textarea>
                                </form>
                                <div class="float-end mt-1" style="display:none" id="btn_inputComment">
                                    <button class="btn btn-light" onclick="hiddenInputCmt()">Đóng</button>
                                    <button form="formPostCmt" type="submit" class="btn btn-primary ">Gửi</button>
                                </div>
                                <script>
                                    function showSelectMember(){
                                        document.getElementById('selectMember').style.display = "inline-block";
                                    }
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
                                        var cmt_files = document.getElementById('cmt_files').value = "";
                                        var selectMember = document.getElementById('selectMember').style.display = "none";
                                        var inputMember = document.getElementById('selectMember').value = "";
                                        var inputMember = document.getElementById('txtArea').value = "";
                                    }
                                </script>
                            </div>
                            @foreach ($comment as $cmt)
                                <div class="">
                                    <strong>
                                        {{$cmt->member->user->username}}
                                    </strong> 
                                    <small class="text-secondary">
                                        {{$cmt->getTime($cmt->created_at)}}
                                    </small>
                                    <p class="issue-desc mb-0">
                                        @if ($cmt->tag != null)
                                            <span class="text-primary">{{$cmt->getTagUsername($cmt->tag)}}</span>
                                        @endif
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
                                        @if ($cmt->member->userID == session()->get("userID"))
                                            <small>
                                                <a class="text-decoration-none text-secondary me-2" href="" data-bs-toggle="modal" data-bs-target="#repCmt{{$cmt->cmtID}}">Trả lời</a> 
                                                <a class="text-decoration-none text-secondary me-2" href="" data-bs-toggle="modal" data-bs-target="#editCmt{{$cmt->cmtID}}">Sửa</a> 
                                                <a class="text-decoration-none text-secondary" href="" data-bs-toggle="modal" data-bs-target="#deleteCmt{{$cmt->cmtID}}">Xóa</a>
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                @foreach ($cmt->getRepCmt($cmt->cmtID) as $repCmt)
                                    <div class="ms-5">
                                        <strong>
                                            {{$repCmt->member->user->username}}
                                        </strong> 
                                        <small class="text-secondary">
                                            {{$repCmt->getTime($repCmt->created_at)}}
                                        </small>
                                        <p class="issue-desc mb-0">
                                            <span class="text-primary">
                                                {{$cmt->member->user->username}}
                                            </span>  
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
                                            @if ($repCmt->member->userID == session()->get("userID"))
                                                <small>
                                                    <a class="text-decoration-none text-secondary me-2" href="" data-bs-toggle="modal" data-bs-target="#editCmt{{$repCmt->cmtID}}">Sửa</a> 
                                                    <a class="text-decoration-none text-secondary" href="" data-bs-toggle="modal" data-bs-target="#deleteCmt{{$repCmt->cmtID}}">Xóa</a>
                                                </small>
                                            @endif
                                            <hr>
                                        </div>
                                    </div>

                                    {{--  Modal trả lời bình luận  --}}
                                    <div class="modal fade" id="repCmt{{$repCmt->cmtID}}" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Trả lời bình luận</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{route('repComment', ['cmtID' => $repCmt->cmtID])}}" method="POST" enctype="multipart/form-data">
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
                <div class="col-4 p-0">
                    <div class="overflow-auto mb-md-0 me-md-3" style="max-height: 495px">
                        {{-- Main Button --}}
                        <div class="mb-4 mt-2">
                            <form style="max-width:50%" action="{{route('workflow', ['issueID' => $issue->issueID])}}" method="POST" id="formWorkflow{{$issue->issueID}}">
                                @csrf
                                @method('PUT')
                                <select class="form-control" name="workflowID" id="" onchange="udpWorkflow{{$issue->issueID}}()" @if ($project->isViewer($project->proID)) disabled @endif>
                                    @if ($issue->workflow->workflowID == 1)
                                        <option value="1" selected>Open</option>
                                        <option value="2">In Progress</option>
                                        <option value="3">Resolved</option>
                                        <option value="5">Closed</option>
                                    @endif
                                    @if ($issue->workflow->workflowID == 2)
                                        <option value="2" selected>In Progress</option>
                                        <option value="1">Open</option>
                                        <option value="3">Resolved</option>
                                        <option value="5">Closed</option>
                                    @endif
                                    @if ($issue->workflow->workflowID == 3)
                                        <option value="3" selected>Resolved</option>
                                        <option value="4">Reopen</option>
                                        <option value="5">Closed</option>
                                    @endif
                                    @if ($issue->workflow->workflowID == 4)
                                        <option value="4" selected>Reopen</option>
                                        <option value="2">In Progress</option>
                                        <option value="3">Resolved</option>
                                        <option value="5">Closed</option>
                                    @endif
                                    @if ($issue->workflow->workflowID == 5)
                                        <option value="5" selected>Closed</option>
                                        <option value="4">Reopen</option>
                                    @endif
                                </select>
                            </form>
                            <script>
                                function udpWorkflow@php echo json_encode($issue->issueID);@endphp(){
                                    var formWorkflow = '@php echo 'formWorkflow' .json_encode($issue->issueID);@endphp';
                                    var formWorkflow@php echo json_encode($issue->issueID);@endphp = document.getElementById(formWorkflow);
                                    formWorkflow@php echo json_encode($issue->issueID);@endphp.submit();
                                }
                            </script>
                        </div>

                        {{-- Chi tiết --}}
                        <div>
                            <table style="width:100%">
                                <tr>
                                    <th class="pb-4">
                                        Người thực hiện
                                    </th>
                                    <td class="pb-4">
                                        {{$issue->member->user->username ?? null}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pb-4">
                                        Người báo cáo
                                    </th>
                                    <td class="pb-4">
                                        {{$issue->getReporter($issue->reporter)}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pb-4">
                                        Độ ưu tiên
                                    </th>
                                    <td class="pb-4">
                                        {{$issue->priority->priority}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pb-4">
                                        Ước tính ban đầu
                                    </th>
                                    <td class="pb-4">
                                        <span class="badge bg-secondary">
                                            {{$issue->original_estimate}}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pb-4">
                                        OLA
                                    </th>
                                    <td class="pb-4">
                                        <span class="badge bg-secondary">
                                            @if ($issue->equalDate($issue->ola, $issue->created_at))
                                                {{$issue->getOLA($issue->issueID, carbon\Carbon::now()->addHour(7) )}}p
                                            @else
                                                {{$issue->getOLA($issue->issueID, carbon\carbon::create($issue->ola) )}}p
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
                                                @if ($issue->workflowID != 5)
                                                    {{$issue->getSLA($issue->issueID, carbon\Carbon::now()->addHour(7) )}}h
                                                @else
                                                    {{$issue->getSLA($issue->issueID, carbon\carbon::create($issue->sla) )}}h
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
                                        {{$issue->date->formatDate($issue->date->startDate)}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Dự kiến hoàn thành
                                    </th>
                                    <td>
                                        {{$issue->date->formatDate($issue->date->endDate)}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
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
                <form action="{{route('issue.update', ['issueID' => $issue->issueID])}}" method="POST">
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
                        <input class="form-control" type="text" name="summary" id="summary" value="{{$issue->summary}}">
                    </div>

                    <div class="form-group">
                        <label for="issueDesc">Mô tả công việc</label>
                        <textarea class="form-control" type="text" name="issueDesc" id="issueDesc" cols="30" rows="3">{{$issue->issueDesc}}</textarea>
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
                                <option @if ($issue->reporter == $mem->memID)
                                selected
                                @endif value="{{$mem->memID}}">{{$mem->user->username}} - {{$mem->role->role}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="priorityID">Độ ưu tiên</label>
                        <select class="form-control" name="priorityID" id="priorityID">
                            @foreach ($priority as $priority)
                                <option @if ($priority->priorityID == $issue->priorityID)
                                selected
                                @endif value="{{$priority->priorityID}}">{{$priority->priority}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="startDate">Từ ngày</label>
                        <input class="form-control" type="date" name="startDate" id="startDate" value="{{$issue->date->startDate}}" >
                    </div>

                    <div class="form-group">
                        <label for="endDate">Đến ngày</label>
                        <input class="form-control" type="date" name="endDate" id="endDate" value="{{$issue->date->endDate}}">
                    </div>

                    <div class="form-group">
                        <label for="original_estimate">Ước tính thời gian làm</label>
                        <input class="form-control" type="text" name="original_estimate" id="original_estimate" min=0 value="{{$issue->original_estimate}}">
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
    <div class="modal fade" id="shareIssue" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Chia sẻ Issue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('postShareIssue', ['issueID' => $issue->issueID])}}" method="POST">
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
</div>
@endsection