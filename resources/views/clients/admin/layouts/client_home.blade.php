<!doctype html>
<html class="no-js" lang="zxx">


<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/index-8.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 12 Feb 2025 04:54:29 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="flash-error" content='@json(session('error'))'>
    <meta name="flash-success" content='@json(session('success'))'>
    <meta name="validation-errors" content='@json(isset($errors) ? $errors->all() : [])'>

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="{{ asset('assets/clients/img/favicon.png') }}" type="image/x-icon" />
    <!-- Font Icons css -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/font-icons.css') }}">
    <!-- plugins css -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/plugins.css') }}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/style.css') }}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/responsive.css') }}">
</head>

<body>
    <!-- Body main wrapper start -->
    <div class="body-wrapper">
        @include('clients.admin.layouts.partials.header_home')
        <main>
            @yield('content')
        </main>

        @include('clients.admin.layouts.partials.footer_home')

    </div>
    <!-- Body main wrapper end -->

    <!-- preloader area start -->
    <div class="preloader d-none" id="preloader">
        <div class="preloader-inner">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>
    <!-- preloader area end -->

    <!-- All JS Plugins -->
    <script src="{{ asset('assets/clients/js/plugins.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('assets/clients/js/main.js') }}"></script>
    <!-- Custom JS (cart, validation, mini-cart ajax) -->
    <script src="{{ asset('assets/clients/js/custom.js') }}?v={{ filemtime(public_path('assets/clients/js/custom.js')) }}"></script>

    <script>
        (function () {
            const flashError = JSON.parse(document.querySelector('meta[name="flash-error"]')?.content ?? 'null');
            const flashSuccess = JSON.parse(document.querySelector('meta[name="flash-success"]')?.content ?? 'null');
            const validationErrors = JSON.parse(document.querySelector('meta[name="validation-errors"]')?.content ?? '[]');

            function notify(type, message, title) {
                if (!message) return;

                if (window.toastr && typeof window.toastr[type] === 'function') {
                    if (typeof window.toastr.clear === 'function') {
                        window.toastr.clear();
                    }
                    window.toastr[type](message, title || (type === 'success' ? 'Thành công' : 'Lỗi'));
                    return;
                }

                window.alert(((title ? title + ': ' : '') + String(message)).replace(/<br\s*\/?\s*>/gi, '\n'));
            }

            if (flashError) {
                notify('error', flashError, 'Lỗi');
            }

            if (flashSuccess) {
                notify('success', flashSuccess, 'Thành công');
            }

            if (Array.isArray(validationErrors) && validationErrors.length) {
                validationErrors.forEach(function (msg) {
                    notify('error', msg, 'Lỗi');
                });
            }
        })();
    </script>

</body>

</html>