<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CPNS</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page dark-mode">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <span class="h1">Login</span>
            </div>
            <div class="card-body">
                <form class="frm-login">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control email" name="email" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control password" name="password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3 text-center">
                        <p>email: admin@mail.com</p>
                    </div>
                    <div class="input-group mb-3 text-center">
                        <p>password: 123456</p>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-12">
                            <button type="button" class="btn btn-primary btn-block" onclick="login()">Login</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>

    <script>
        var baseUrl = "{{ url('/') }}";

        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function login() {
            if ($(".email").val() == "" || $(".email").val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Email harus diisi"
                });

            } else if ($(".password").val() == "" || $(".password").val() == null) {
                Toast.fire({
                    icon: "warning",
                    title: "Password harus diisi"
                });

            } else {
                console.log("{{ route('post.login') }}");
                $.ajax({
                    url: "{{ route('post.login') }}",
                    type: "post",
                    data: $(".frm-login").serialize(),
                    success: function(response) {
                        console.log(response)
                        if (response.status == "berhasil") {
                            Toast.fire({
                                    icon: "success",
                                    title: "Login berhasil"
                                })
                                .then(function() {
                                    window.location.href = baseUrl + response.url;
                                });

                        } else if (response.status == "gagal") {
                            Toast.fire({
                                icon: "error",
                                title: response.message
                            });
                        }
                    },
                    error: function(request, status, error) {
                        Toast.fire({
                            icon: "error",
                            title: "Terjadi kesalahan"
                        });
                        console.log(request.responseText);
                    }
                });
            }
        }
    </script>
</body>

</html>
