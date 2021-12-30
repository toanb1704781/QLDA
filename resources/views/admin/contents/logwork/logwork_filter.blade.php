
@extends('admin.layouts.master')
@section('title', 'Báo cáo chấm công')

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
                    <a href="{{route('quanlyduan.show', ['quanlyduan' => $project->proID])}}" class="nav-link link-dark" aria-current="page">
                        Công việc
                    </a>
                </li>
                <li>
                    <a href="" class="nav-link active" aria-current="page">
                        Báo cáo chấm công
                    </a>
                </li>
                <hr>
                <li>
                    <a href="{{route('getSettingDetails', ['proID' => $project->proID])}}" class="nav-link link-dark" aria-current="page">
                        Thiết lập
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-9 mb-0">
        <div class="row" style="margin-right: -120px">
            <nav style="--bs-breadcrumb-divider: '>'; padding-top: 10px;" aria-label="breadcrumb" >
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('quanlyduan.index')}}">
                            Dự án
                        </a
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('quanlyduan.show', ['quanlyduan' => $project->proID])}}">
                            {{$project->proName}}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Báo cáo giờ công</li>
                </ol>
            </nav>
            <div class="col-sm-12">
                <h2>Báo cáo</h2>
                <strong>Lọc theo:</strong>
                <select style="width: 20%; display:inline-block;" class="form-select mb-2 me-2" name="filterReport" id="filterReportID" onchange="filterReport()">
                    <option value="">...</option>
                    <option value="1">Theo tháng</option>
                    <option value="2">Theo ngày</option>
                </select>
                <form style="display: none" action="{{route('logwork.filter', ['proID' => $project->proID])}}"  method="post" id="formFilterReport">
                    @csrf
                </form>
                <span id="selectMonth" style="display: none">
                    <label for=""><strong>Chọn tháng: </strong></label>
                    <input onchange="submitForm()" name="month" form="formFilterReport" style="display:inline-block; width: 20%"class="form-control" type="month">
                </span>
                <span id="selectDate" style="display: none">
                    <label for=""><strong>Từ ngày: </strong></label>
                    <input name="formDate" form="formFilterReport" style="display:inline-block; width: 20%" class="form-control" type="date">
                    <label for=""><strong>Đến ngày: </strong></label>
                    <input onchange="submitForm()"  name="toDate" form="formFilterReport" style="display:inline-block; width: 20%"class="form-control" type="date">
                </span>
                <script>
                    function filterReport(){
                        var filterReportID = document.getElementById('filterReportID').value;
                        if(filterReportID == 1){
                            var selectDate = document.getElementById('selectDate').style.display = "none";
                            var selectDate = document.getElementById('selectMonth').style.display = "inline";
                        }else{
                            var selectDate = document.getElementById('selectDate').style.display = "inline";
                            var selectDate = document.getElementById('selectMonth').style.display = "none";
                        }
                    }
                    function submitForm(){
                        var formFilterReport = document.getElementById('formFilterReport');
                        formFilterReport.submit();
                    }
                </script>
            </div>
            <div class="col-sm-6"></div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4 class="text-center">
                    Báo cáo giờ công từ ngày {{$form}}/{{$month}} đến ngày {{$to}}/{{$month}}
                </h4>
            </div>
            <div class="overflow-auto" style="min-height: 375px">
                <table  class="table table-hover table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <th>Ngày</th>
                        @for ($i = $form; $i <= $to; $i++)
                            <th>
                                {{$i}}
                            </th>
                        @endfor
                    </thead>
                    <tbody>
                        @foreach ($logwork as $logwork)
                            <tr>
                                <th>
                                    {{$logwork->issue->summary}} <br>
                                    <small>
                                        {{$logwork->issue->member->user->username}} <br>
                                        {{--  Thời gian đã làm: {{$logwork->timeRemaining += $logwork->timeRemaining}} <br>
                                        Thời gian còn lại: {{$logwork->timeRemaining}}  --}}
                                    </small>
                                </th>
                                @for ($i = $form; $i <= $to; $i++)
                                    <td>
                                        @if ($i == $logwork->getDay($logwork->created_at))
                                            {{App\Models\Timeunit::getTimeUnit($logwork->workingTime)}} giờ
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection