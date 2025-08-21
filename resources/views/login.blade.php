<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Login</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('template2/assets/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('template2/assets/css/styles.min.css') }}">
</head>

<body>
    <!-- Wrapper Utama -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="/" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="{{ asset('assets/logokesehatan.png') }}" width="280" alt="">
                                </a>
                                <p class="text-center">Silahkan Login Sebagai admin</p>
                                <div class="card-body">
                                    @if ($errors->has('login'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('login') }}
                                        </div>
                                    @endif
                                    <form action="/loginuser" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Username</label>
                                            <input type="email" class="form-control" id="exampleInputEmail1"
                                                name="email" aria-describedby="emailHelp">
                                        </div>
                                        <div class="mb-4">
                                            <label for="exampleInputPassword1" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="exampleInputPassword1"
                                                name="password">
                                        </div>

                                        <div class="col-12">
                                            <button type="submit"
                                                class="btn btn-primary w-100 py-2 fs-4 mb-4 rounded-2">Masuk</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('template2/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template2/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
