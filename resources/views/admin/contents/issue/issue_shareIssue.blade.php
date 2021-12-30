@extends('admin.layouts.master')

@section('title', 'Công việc chia sẻ với tôi')

@section('content')
<div class="card" style="padding: 20px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Công việc chia sẻ với tôi</h2>
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
                    <th>Từ</th>
                    <th>Đến</th>
                </tr>
            </thead>
            <tbody>
                @if ($issue)
                    @foreach ($issue as $issue)
                        <tr>
                            <td>
                                @if ($issue->issue_type->typeID == 3)
                                    <span class="badge bg-info">{{$issue->issue_type->issue_type}}</span>
                                @endif
                                @if ($issue->issue_type->typeID == 4)
                                    <span class="badge bg-danger">{{$issue->issue_type->issue_type}}</span>
                                @endif
                                <a class="btn" href="{{route('issue.show', ['issueID' => $issue->issueID])}}">
                                    <strong>
                                        {{$issue->summary}}
                                    </strong> 
                                </a>
                                <br>
                                <small>
                                    {{$issue->project->proName}}
                                </small>
                            </td>
                            <td>
                                {{$issue->date->startDate}}
                            </td>
                            <td>
                                {{$issue->date->endDate}}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection