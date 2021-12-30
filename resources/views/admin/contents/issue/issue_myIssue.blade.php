@extends('admin.layouts.master')

@section('title', 'Công việc')

@section('content')
<div class="card" style="padding: 20px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Tất cả công việc</h2>
                    <form action="" style="width: 50%" method="get" id="formFilterMyIssue">
                        <strong>Lọc theo:</strong>
                        <select class="form-control" name="filterIssue" onchange="filter()">
                            <option value="all-issues" @if ($value == 'my-issues' || $value == null)
                            selected
                            @endif>Tất cả công việc</option>
                            <option value="my-issues" @if ($value == 'my-issues')
                            selected
                            @endif>Công việc của tôi</option>
                            <option value="issues-share" @if ($value == 'issues-share')
                            selected
                            @endif>Công việc được chia sẻ</option>
                        </select>
                    </form>
                    <script>
                        function filter(){
                            var formFilterMyIssue = document.getElementById('formFilterMyIssue');
                            formFilterMyIssue.submit();
                        }
                    </script>
                </div>
                <div class="col-sm-6"></div>
            </div>
        </div>
    </section>

    <div class="card-body p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Công việc</th>
                    <th>Thời gian bắt đầu</th>
                    <th>Số ngày thực hiện</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($issue))
                    @foreach ($issue as $issue)
                        <tr>
                            <td>
                                <a class="btn" href="{{route('issue.show', ['issueID' => $issue->issueID])}}">
                                    @if ($issue->issue_type->typeID == 3)
                                        <span class="badge bg-info">{{$issue->issue_type->issue_type}}</span>
                                    @endif
                                    @if ($issue->issue_type->typeID == 4)
                                        <span class="badge bg-danger">{{$issue->issue_type->issue_type}}</span>
                                    @endif
                                    <span class="text-black-50 ms-1 me-1">
                                        {{$issue->issueKey}}
                                    </span>
                                    <span>
                                        {{$issue->summary}}
                                    </span>
                                </a>
                            </td>
                            <td >
                                {{$issue->date->formatDate($issue->date->startDate)}}
                            </td>
                            <td>
                                {{$issue->date->countDay($issue->date->dateID)}} ngày
                            </td>
                            <td>
                                {{$issue->workflow->workflow}}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection