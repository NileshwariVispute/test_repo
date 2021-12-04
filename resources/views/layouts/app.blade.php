<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts -->
    <link href="{{ asset('asset/fonts/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap.min.css" rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <link href="{{ asset('asset/css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('asset/js/jquery/jquery.min.js') }}" defer></script>
    <script src="{{ asset('asset/js/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('asset/js/jquery-easing/jquery.easing.min.js') }}" defer></script>
    <script src="{{ asset('asset/js/datatables/jquery.dataTables.min.js') }}" defer></script>
    <script src="{{ asset('asset/js/datatables/dataTables.bootstrap4.min.js') }}" defer></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js" defer></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/responsive.bootstrap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js" defer></script>
    <script src="{{ asset('asset/js/jquery/jquery.form.js') }}" defer></script>
    <script src="{{ asset('asset/js/bootstrap/js/sweetalert.min.js') }}" defer></script>
    <script src="{{ asset('asset/js/app.js') }}" defer></script>
    <script src="{{ asset('asset/js/custom.js') }}" defer></script>
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center sidebarToggle">
                <div class="sidebar-brand-text mx-3">Admin</div>
                <div class="sidebar-brand-icon">
                    <i class="fas fa-align-right" style="font-size: 1.7rem;"></i>
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" />

            <!-- Heading -->
            <div class="sidebar-heading">
                Others
            </div>

            <!-- Nav Item - Subject -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('view-subject') }}">
                    <i class="fas fa-book"></i>
                    <span>Subjects</span>
                </a>
            </li>

            <!-- Nav Item - Classes -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('view-classes') }}">
                    <i class="fas fa-user"></i>
                    <span>Classes</span>
                </a>
            </li>

            <!-- Nav Item - Video Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-video"></i>
                    <span>Videos</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Videos Components:</h6>
                        <a class="collapse-item" href="{{ route('add-video') }}">Add New Video</a>
                        <a class="collapse-item" href="{{ route('view-videos') }}">View Videos</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Users -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('view-users') }}">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>

            <!-- Nav Item - Users -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('payments') }}">
                    <i class="far fa-credit-card"></i>
                    <span>Payment Details</span>
                </a>
            </li>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->
        @yield('content')
    </div>
</body>
</html>
