@extends('admin.layouts.master')

@section('title', 'Trang chủ')

@section('content')
<div class="ms-4 me-4">
    <section class="content-header">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h2>Trang chủ</h2>
            </div>
            <div class="col-sm-6"></div>
        </div>
    </section>

    <div class="row">
        <div class="col-3">
            <section class="content-header mb-3">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="text-secondary">Dự án gần đây</h5>
                    </div>
                    <div class="col-sm-6"></div>
                </div>
            </section>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($access as $access)
                    <div class="col-12">
                        <a href="{{route('quanlyduan.show', ['quanlyduan' => $access->proID])}}" class="text-decoration-none text-dark">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">
                                    {{Str::of($access->project->proName)->limit(30)}}
                                </h6>
                                <table class="w-100 text-secondary" style="font-size: 14px">
                                    @if (session()->get('userID') == 1)
                                        <tr>
                                            <td>
                                                Tổng công việc
                                            </td>
                                            <td class="text-end">
                                                {{$access->project->countMyIssue($access->proID, 0)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Tổng công việc hoàn thành
                                            </td>
                                            <td class="text-end">
                                                {{$access->project->countMyIssue($access->proID, 5)}}
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>
                                                Công việc của tôi
                                            </td>
                                            <td class="text-end">
                                                {{$access->project->countMyIssue($access->proID, 0)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Đã hoàn thành
                                            </td>
                                            <td class="text-end">
                                                {{$access->project->countMyIssue($access->proID, 5)}}
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        </a>
                    </div>
                @endforeach
                @isset($assess)
                    <div class="col-12 text-center">
                        <a class="text-decoration-none" href="{{route('quanlyduan.index')}}">
                            Xem tất cả
                        </a>
                    </div>
                @endisset
            </div>
        </div>

        <div class="col-9">
            <section class="content-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="text-secondary">Công việc</h5>
                    </div>
                    <div class="col-sm-6 text-end"></div>
                </div>
            </section>
            <table class="table table-hover  align-middle">
                <thead>
                    <tr>
                        <th>Công việc</th>
                        <th>Dự kiến hoàn thành</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($issue)
                        @foreach ($issue as $issue)
                            <tr>
                                <td>
                                    @if ($issue->issue_type->typeID == 3)
                                        <span class="badge bg-info">{{$issue->issue_type->issue_type}}</span>
                                    @endif
                                    @if ($issue->issue_type->typeID == 4)
                                        <span class="badge bg-danger">{{$issue->issue_type->issue_type}}</span>
                                    @endif
                                    <a class="btn text-start " href="{{route('issue.show', ['issueID' => $issue->issueID])}}">
                                        <span>
                                            {{$issue->summary}} <br>
                                            <small class="text-black-50">
                                                {{$issue->issueKey}} • {{$issue->project->proName}}
                                            </small>
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    {{$issue->date->formatDate($issue->date->endDate)}}
                                </td>
                                <td>
                                    {{$issue->workflow->workflow}}
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection