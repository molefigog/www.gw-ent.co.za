<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description">
    <meta content="Themesbrand" name="author">
    <!-- App favicon -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    @yield('head')

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <!-- Scripts -->
    @stack('aplayer')
    @livewireStyles
    <style>
        .dropdown-menu {
            --bs-dropdown-bg: #36394cf2;
        }
    </style>
</head>
{{--
@php
    $artists = App\Models\User::orderBy('name')->get();
@endphp --}}

<body data-topbar="light" data-layout="horizontal">
    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('layouts.header')

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid text-center">
                    {{ $slot }}
                    @include('layouts.modal')
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @yield('audio')
            <p class="text center">Select Songs By Artist</p>

            <footer class="footer">
                <div class="container-fluid">
                    <p class="text center">Select Songs By Artist</p>
                    <ul class="nav">
                        @foreach ($artists as $artist)
                            @php
                                $musicCount = DB::table('music_user')
                                    ->where('user_id', $artist->id)
                                    ->count();
                            @endphp

                            @if ($musicCount > 0)
                                <li class="nav-item text-uppercase">
                                    <div class="p-2">
                                        <a class="btn btn-outline-info btn-sm text-truncate"
                                            href="{{ url('artist', str_replace(' ', '_', $artist->name)) }}"
                                            style="max-width: 150px;">
                                            {{ $artist->name }}
                                        </a>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="row">
                        <div class="col-12">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>{{$setting->site}}<span class="d-none d-sm-inline-block"> | <i
                                    class="mdi mdi-heart text-danger"></i> Help <a href="mailto:{{$admin->email}}" target="_blank">Desk</a></span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="rightbar-title px-3 py-4">
                <a href="javascript:void(0);" class="right-bar-toggle float-end">
                    <i class="mdi mdi-close noti-icon"></i>
                </a>
                <h5 class="m-0">Settings</h5>
            </div>
            <!-- Settings -->
            <hr class="mt-0" />

            <div class="p-4">

                <div class="form-check form-switch mb-3">
                    <input type="checkbox" class="form-check-input theme-choice" id="light-mode-switch" checked />
                    <label class="form-check-label" for="light-mode-switch">Light Mode</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input type="checkbox" class="form-check-input theme-choice" id="dark-mode-switch"
                        data-bsStyle="{{ asset('assets/css/bootstrap-dark.min.css') }}"
                        data-appStyle="{{ asset('assets/css/app-dark.min.css') }}" />
                    <label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
                </div>
            </div>

        </div> <!-- end slimscroll-menu-->
    </div>

    @stack('modals')

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <!-- Peity chart-->
    <script src="{{ asset('assets/libs/peity/jquery.peity.min.js') }}"></script>
    <script>
        const BootstrapMin = "{{ asset('assets/css/bootstrap.min.css') }}";
        const AppCss = "{{ asset('assets/css/app.min.css') }}";
        const BootstrapRtl = "{{ asset('assets/css/bootstrap-rtl.min.css') }}";
        const AppRtl = "{{ asset('assets/css/app-rtl.min.css') }}";
    </script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('player')

    @stack('modal')
    @stack('pal')
    @stack('upload_status')
    @stack('updated')
    @stack('mpesa')

    @livewireScripts
</body>

</html>
