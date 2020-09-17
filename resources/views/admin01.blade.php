@extends('layouts.core.app')

@section('content')

    <!-- Navbar -->
    @include('layouts.admin01.nav')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('layouts.admin01.sidebar')
    <!-- /.Main Sidebar Container -->

    <!-- Content Wrapper. Contains page content -->
    @include('layouts.admin01.content')
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    @include('layouts.admin01.controlbar')
    <!-- /.control-sidebar -->

    <!-- Admin Footer -->
    @include('layouts.admin01.footer')

@endsection
