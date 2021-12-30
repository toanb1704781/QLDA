
@extends('admin.layouts.master')
@section('title', 'Dự án')

@section('sidebar-content')
<div class="row">

    <div class="col-3">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <strong>
                    {{$pro->proName}}
                </strong>   
            </a>
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

    <div class="col-8">
        <div class="row mb-2">
            <nav style="--bs-breadcrumb-divider: '>'; padding-top: 10px;" aria-label="breadcrumb" >
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('quanlyduan.index')}}">
                            Dự án
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('quanlyduan.show', ['quanlyduan' => $pro->proID ])}}">
                            {{$pro->proName}}
                        </a>
                    </li>
                </ol>
            </nav>

            <div class="col-sm-6">
                <h2>Công việc dự án</h2>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right" style="float: right;">
                    @if ($pro->created_by == session()->get('userID') || App\Models\User::checkAdmin())
                        <a class="btn btn-success btn-sm" href="" data-bs-toggle="modal" data-bs-target="#addIssue">
                            Thêm công việc
                        </a>
                    @endif
                </ol>
            </div>

            {{-- Modal thêm công việc --}}
            <div class="modal fade" id="addIssue" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Thêm công việc</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{route('issue.store', ['proID' => $pro->proID])}}" method="post">
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
                                    <label for="summary">Tóm lược</label>
                                    <input class="form-control" type="text" name="summary" id="summary" placeholder="Nhập tóm lược...">
                                </div>

                                <div class="form-group">
                                    <label for="issueDesc">Mô tả công việc</label>
                                    <textarea class="form-control" type="text" name="issueDesc" id="issueDesc" cols="30" rows="3" placeholder="Mô tả công việc"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="memID">Người thực hiện</label>
                                    <select class="form-control" name="memID" id="memID">
                                        <option value="0">...</option>
                                        @foreach ($member as $mem)
                                        <option value="{{$mem->memID}}">{{$mem->user->username}} - {{$mem->role->role}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="startDate">Từ ngày</label>
                                    <input class="form-control" type="date" name="startDate" id="startDate" >
                                </div>

                                <div class="form-group">
                                    <label for="endDate">Đến ngày</label>
                                    <input class="form-control" type="date" name="endDate" id="endDate" >
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Thêm công việc</button>
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
                            <th>Tóm lược</th>
                            <th>Từ ngày</th>
                            <th>Đến ngày</th>
                            <th>Người thực hiện</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($issue as $issue)
                            <tr>
                                <td></td>
                                <td>
                                    <a href="{{route('issue.show', ['issueID' => $issue->issueID])}}}">
                                        {{$issue->summary}}
                                    </a>
                                </td>
                                <td>
                                    {{$issue->date->startDate}}
                                </td>
                                <td>
                                    {{$issue->date->endDate}}
                                </td>
                                <td>
                                    {{$issue->member->user->username}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> 
    </div>

</div>
@endsection