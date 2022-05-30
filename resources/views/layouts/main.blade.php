<!DOCTYPE html>
<html lang="id">

<head>
    @include('layouts.partials.head')
    @include('layouts.partials.style')
    @yield('extra_styles')
</head>

<body class="hold-transition sidebar-mini dark-mode">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.partials.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('layouts.partials.sidebar')
        <!-- /.main sidebar container -->

        <!-- Content Wrapper. Contains page content -->
        @yield('contents')
        <!-- /.content-wrapper -->

        <!-- Footer -->
        @include('layouts.partials.footer')
        <!-- /.footer -->

        <!-- Control Sidebar -->
        @include('layouts.partials.control-sidebar')
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    @include('layouts.partials.script')
    @yield('extra_scripts')
</body>

</html>
