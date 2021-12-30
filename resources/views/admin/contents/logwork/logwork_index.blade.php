@extends('admin.layouts.master')
@section('title', 'Báo cáo chấm công')

@section('sidebar-content')
<div class="row ms-4 me-4">
    <div class="col-12">
        <div class="row" style="margin-right: -120px">
            <nav style="--bs-breadcrumb-divider: '>'; padding-top: 10px;" aria-label="breadcrumb" >
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="">
                            Trang chủ
                        </a
                    </li>
                    <li class="breadcrumb-item">
                        Báo cáo chấm công
                    </li>
                </ol>
            </nav>

            <div class="col-sm-12 mb-3">
                <h2>Báo cáo chấm công</h2>
                <form style="display: none" action="{{route('logwork.filter')}}"  method="get" id="formFilterReport">
                </form>
                <label for="proID"><strong>Dự án</strong></label>
                <select form="formFilterReport" style="display:inline-block; width: 25%" class="form-select me-4" name="proID" id="proID" onchange="submitForm()">
                    <option value="tat-ca" selected>Tất cả dự án</option>
                    @foreach (App\Models\Project::getAllMyProject() as $project)
                        <option  @if (($valueProID ?? null) == $project->proID)
                        selected
                        @endif value="{{$project->proID}}">{{$project->proName}}</option>
                    @endforeach
                </select>
                <label for="memID"><strong>Thành viên</strong></label>
                <select form="formFilterReport" style="display:inline-block; width: 15%" class="form-select me-4" name="memID" id="memID" onchange="submitForm()">
                    <option value="" selected>Tất cả thành viên</option>
                    @isset($allMember)
                        @foreach ($allMember as $mem)
                            <option @if (($valueMemID ?? null) == $mem->memID)
                            selected
                            @endif value="{{$mem->memID}}">{{$mem->user->username}}</option>
                        @endforeach
                    @endisset
                </select>
                <label for=""><strong>Từ</strong></label>
                <input onchange="submitForm()" name="fromDate" form="formFilterReport" style="display:inline-block; width: 15%" class="form-control" type="date" value="{{$valueFrom}}">
                <label for=""><strong>Đến</strong></label>
                <input onchange="submitForm()"  name="toDate" form="formFilterReport" style="display:inline-block; width: 15%"class="form-control" type="date" value="{{$valueTo}}">
                <script>
                    function submitForm(){
                        var formFilterReport = document.getElementById('formFilterReport');
                        formFilterReport.submit();
                    }
                </script>
            </div>
            @if (isset($logwork))
                <div class="overflow-auto" style="min-height: 375px; max-width: 1300px">
                    <table  class="table table-hover table-bordered table-striped">
                        <thead class="bg-secondary bg-gradient text-white">
                        <th></th>
                        @for ($i = $from; $i <= $to; $i++)
                        <th class="text-center">
                        {{$i}} <br>
                        @if ($data_total[$i] != null)
                        <span class="badge bg-success">Tổng: {{$data_total[$i]}}</span>
                        @endif
                        </th>
                        @endfor
                        </thead>
                        <tbody>
                            @foreach ($logwork as $logwork)
                                <tr>
                                    <td>
                                        <a class="text-decoration-none" href="{{route('issue.show', ['issueID' => $logwork->issueID])}}">
                                            @if ($logwork->issue->issue_type->typeID == 3)
                                                <span class="badge bg-info">Task</span>
                                            @endif
                                            @if ($logwork->issue->issue_type->typeID == 4)
                                                <span class="badge bg-danger">Bug</span>
                                            @endif
                                            <span class="text-black-50 ms-1 me-1">
                                                {{$logwork->issue->issueKey}}
                                            </span>
                                            <span class="text-dark">
                                                {{$logwork->issue->summary}}
                                            </span>
                                        </a>
                                    </td>
                                    @for ($i = $from; $i <= $to; $i++)
                                        <td class="text-center">
                                            @if ($i == $logwork->getDay($logwork->created_at))
                                                <a href="{{route('logwork.logworkLocation', ['locationID' => $logwork->locationID])}}" target="_blank" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="left" title="{{$logwork->logworkNote}}">
                                                    {{App\Models\Timeunit::getTimeUnit($logwork->workingTime)}}
                                                </a>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection