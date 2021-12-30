<!doctype html>
<html lang="en">
<head>
@include('admin.layouts.header')
</head>
<body>
@include('admin.layouts.navbar')

@yield('sidebar-content')

@yield('content')

@include('admin.layouts.script')
</body>
</html>