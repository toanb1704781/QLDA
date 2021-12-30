<nav class="navbar navbar-expand-lg navbar-light ps-3 p3-3 mb-3" style="border-bottom: 1px solid rgb(102, 102, 102)">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{route('home.index')}}">
            QLDASoftWare
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link active" href="{{route('home.index')}}">
                        Trang chủ
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="proDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dự án
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="proDropdown">
                        <strong class="ms-3">Gần đây</strong>
                        @foreach (App\Models\Access::recent_project() as $access)
                            <li>
                                <a class="dropdown-item" href="{{route('quanlyduan.show', ['quanlyduan' => $access->proID])}}">
                                    {{$access->project->proName}}
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{route('quanlyduan.index')}}">
                                Tất cả dự án
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addPro">
                                Tạo dự án
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="proDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Công việc
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="proDropdown">
                        <strong class="ms-3">Gần đây</strong>
                        @foreach (App\Models\Access::recent_issue() as $access)
                            <li>
                                <a class="dropdown-item" href="{{route('issue.show', ['issueID' => $access->issueID])}}">
                                    @if ($access->issue->issue_type->typeID == 3)
                                        <span class="badge bg-info">{{$access->issue->issue_type->issue_type}}</span>
                                    @endif
                                    @if ($access->issue->issue_type->typeID == 4)
                                        <span class="badge bg-danger">{{$access->issue->issue_type->issue_type}}</span>
                                    @endif
                                    {{$access->issue->summary}}
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{route('myIssue')}}">
                                Tất cả công việc
                            </a>
                        </li>
                    </ul>
                </li>
                @if (session()->get("userID") != 1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="proDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Chấm công
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="proDropdown">
                            <li>
                                <a onclick="getLocation()" class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#logWork">
                                    Chấm công
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{route('logwork.myLogwork')}}">
                                    Lịch sử chấm công
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('logwork.index')}}">
                        Báo cáo chấm công
                    </a>
                </li>

                {{--  Tạo mới  --}}
                <li class="nav-item dropdown">
                    <button class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#addIssue">
                        Tạo mới
                    </button>
                </li>
            </ul>

            {{--  Tìm kiếm  --}}
            <form class="d-flex me-3">
                <input class="form-control" type="search" placeholder="Tìm kiếm" aria-label="Search">
            </form>

            <ul class="nav justify-content-end">
                {{--  Thông báo  --}}
                <li class="nav-item dropdown">
                    <button type="button" class="btn position-relative btn-sm mt-2 me-2" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if (App\Models\Notification::getNotifications()->isNotEmpty())
                            <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">aaaNew alerts</span>
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu overflow-auto" aria-labelledby="navbarDropdown" style="margin: 10px -250px; max-height:300px">
                        @foreach (App\Models\Notification::getNotifications() as $notification)
                            <li>
                                <a class="dropdown-item bg-light" href="{{$notification->notiUrl}}">
                                <div>
                                    <strong>
                                        {{$notification->user->username}}
                                    </strong>
                                    {{$notification->notification_type->notiType}}
                                </div>
                                <small>{{$notification->created_at}}</small>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                {{--  Người dùng  --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{App\Models\User::user_current()}}
                    </a>
                    <ul class="dropdown-menu " aria-labelledby="navbarDropdown" style="margin: 3px -70px;">
                        <strong class="ms-3">Thông tin</strong>
                        <li>
                            <a class="dropdown-item" href="./info.html" data-bs-toggle="modal" data-bs-target="#info">
                                Thông tin cá nhân
                            </a>
                        </li>
                        @if (App\Models\User::checkAdmin())
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <strong class="ms-3">Thiết lập</strong>
                            <li>
                                <a class="dropdown-item" href="{{route('quanlytaikhoan.index')}}">
                                    Quản lý tài khoản
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#timeunit">
                                    Thời gian làm việc
                                </a>
                            </li>
                        @endif
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{route('logout')}}">
                                Đăng xuất
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Modal thông tin cá nhân --}}
<div class="modal fade" id="info" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Thông tin cá nhân</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('quanlytaikhoan.update', ['quanlytaikhoan' => App\Models\User::userInfo()->userID])}}" method="post">
                @csrf
                @method('put')
                <div class="modal-body">

                    <div class="form-group">
                        <label for="username">Tài khoản</label>
                        <input class="form-control" type="text" name="username" id="username" value="{{App\Models\User::userInfo()->username}}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="name">Họ tên</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{App\Models\User::userInfo()->information->name}}">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="text" name="email" id="email" value="{{App\Models\User::userInfo()->information->email}}">
                    </div>

                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <input class="form-control" type="text" name="address" id="address" value="{{App\Models\User::userInfo()->information->address}}">
                    </div>

                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input class="form-control" type="text" name="phone" id="phone" value="{{App\Models\User::userInfo()->information->phone}}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal thiết lập thời gian làm việc --}}
<div class="modal fade" id="timeunit" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Thời gian làm việc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('logwork.putTimeunit')}}" method="post">
                @csrf
                @method('put')

                <div class="modal-body">
                    <div class="form-group">
                        <label for="timeUnitDay">Ngày</label>
                        <input class="form-control" type="number" name="timeUnitDay" id="timeUnitDay" min="0" value="{{App\Models\Timeunit::getAllTimeunit('d')}}">
                    </div>
                    <div class="form-group">
                        <label for="timeUnitWeek">Tuần</label>
                        <input class="form-control" type="number" name="timeUnitWeek" id="timeUnitWeek" min="0" value="{{App\Models\Timeunit::getAllTimeunit('w')}}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal chấm công --}}
<div class="modal fade" id="logWork" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Chấm công</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formLogwork" action="{{route('logwork.store')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="issueID">Công việc</label>
                        <select class="form-select" name="issueID" id="issueID" required>
                            <option value="">Chọn công việc</option>
                            @if (App\Models\Issue::getAllIssueLogWork() != null)
                                @foreach (App\Models\Issue::getAllIssueLogWork() as $issue)
                                    <option value="{{$issue->issueID}}">
                                    [{{$issue->issueKey}}] {{$issue->summary}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        {{--  <input class="form-control" type="text" list="issueList" name="issueKey" placeholder="Chọn công việc...">
                        <datalist id="issueList">
                        @if (App\Models\Issue::getAllIssueLogWork() != null)
                        @foreach (App\Models\Issue::getAllIssueLogWork() as $issue)
                        <option value="{{$issue->issueKey}}">
                        {{$issue->issue_type->issue_type}} {{$issue->summary}}
                        </option>
                        @endforeach
                        @endif
                        </datalist>  --}}
                    </div>

                    <div class="form-group">
                        <label for="workingTime">Thời gian chấm công</label>
                        <input class="form-control" type="text" name="workingTime" id="workingTime" placeholder="Ví dụ: 4h (4 giờ), 2d (2 ngày)">
                    </div>

                    <div class="form-group">
                        <label for="created_at">Ngày chấm công</label>
                        <input class="form-control" type="date" name="created_at" id="created_at" value="{{App\Models\Date::getDateNow()}}">
                    </div>

                    <div class="form-group">
                        <label for="logworkNote">Ghi chú</label>
                        <input class="form-control" type="text" name="logworkNote" id="logworkNote" placeholder="Ghi chú...">
                    </div>

                    {{--  Lay toa do hien tai  --}}
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="latitude" id="latitude">

                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Chấm công</button>
                </div>

                <script>
                    function getLocation() {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(showPosition);
                        }
                    }

                    function showPosition(position) {
                        var longitude = document.getElementById('longitude').value = position.coords.longitude;
                        var latitude = document.getElementById('latitude').value = position.coords.latitude;
                        var formLogwork = document.getElementById('formLogwork');
                    }
                </script>
            </form>
        </div>
    </div>
</div> 

{{-- Modal xem báo cáo chấm công --}}
<div class="modal fade" id="reportLogwork" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Xem báo cáo chấm công</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('logwork.filter')}}" method="get">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="proID">Dự án</label>
                        <select class="form-select" name="proID" id="proID" >
                            <option value="">Chọn dự án</option>
                            @if (App\Models\Project::getAllMyProject() != null)
                                @foreach (App\Models\Project::getAllMyProject() as $project)
                                    <option value="{{$project->proID}}">
                                        {{$project->proName}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fromDate">Từ ngày</label>
                        <input class="form-control" type="date" name="fromDate" id="fromDate">
                    </div>

                    <div class="form-group">
                        <label for="toDate">Đến ngày</label>
                        <input class="form-control" type="date" name="toDate" id="toDate" value="{{App\Models\Date::getDateNow()}}">
                    </div>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Xem báo cáo</button>
                </div>
            </form>
        </div>
    </div>
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
                        <input class="form-control" type="text" name="proName" id="proName" onkeypress="myFunction()" placeholder="Nhập tên dự án...">
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
                        <textarea class="form-control" type="text" name="proDesc" id="proDesc" cols="30" rows="3" placeholder="Mô tả dự án..."></textarea>
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

{{-- Modal thêm issue --}}
{{--  <div class="modal fade" id="addIssue" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Thêm mới Issue</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{route('quanlytaikhoan.update', ['quanlytaikhoan' => App\user::userInfo()->userID])}}" method="post">
@csrf
<div class="modal-body">

<div class="form-group">
<label for="proID">Dự án</label>
<select class="form-control" name="proID" id="proID">
<option value="">...</option>
@foreach (App\project::allProject() as $pro)
<option value="{{$pro->proID}}">{{$pro->proName}}</option>
@endforeach
</select>
</div>

<div class="form-group">
<label for="typeID">Kiểu issue</label>
<select class="form-control" name="typeID" id="typeID">
<option value="">...</option>
@foreach (App\issue_type::allIssueType() as $type)
<option value="{{$type->typeID}}">{{$type->issue_type}}</option>
@endforeach
</select>
</div>

<div class="form-group">
<label for="summary">Tóm lược</label>
<input class="form-control" type="text" name="summary" id="summary" placeholder="Nhập tóm lược...">
</div>

<div class="form-group">
<label for="memID">Tóm lược</label>
<input class="form-control" type="text" name="summary" id="summary" placeholder="Nhập tóm lược...">
</div>

</div>

<div class="modal-footer">
<button type="submit" class="btn btn-primary">Lưu thông tin</button>
</div>
</form>
</div>
</div>
</div>  --}}

