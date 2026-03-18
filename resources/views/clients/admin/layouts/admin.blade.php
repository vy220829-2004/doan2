<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="flash-error" content='@json(session('error'))'>
    <meta name="flash-success" content='@json(session('success'))'>
  <link rel="icon" href="{{ asset('assets/clients/admin/vendors/bootstrap/site/favicon.ico') }}" type="image/ico" />

  <title>@yield('title', 'Gentelella Alela!')</title>

    <!-- Bootstrap -->
    <link href="{{asset('assets/clients/admin/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('assets/clients/admin/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('assets/clients/admin/vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{asset('assets/clients/admin/vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="{{asset('assets/clients/admin/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{asset('assets/clients/admin/vendors/jqvmap/dist/jqvmap.min.css')}}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{asset('assets/clients/admin/vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{asset('assets/clients/admin/build/css/custom.min.css')}}" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div id="simple-toast-container" style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;pointer-events:none;"></div>

    @php
      $activeGuard = request()->is('staff') || request()->is('staff/*') ? 'staff' : 'admin';
      $authUser = \Illuminate\Support\Facades\Auth::guard($activeGuard)->user();
      $logoutRoute = $activeGuard === 'staff' ? route('staff.logout') : route('admin.logout');
      $dashboardRoute = $activeGuard === 'staff' ? route('staff.dashboard') : route('admin.dashboard');
    @endphp

    <form id="admin-logout-form" action="{{ $logoutRoute }}" method="POST" style="display:none;">
      @csrf
    </form>
    <div class="container body">
      <div class="main_container">
    @include('clients.admin.partials.side-bar')
    @include('clients.admin.partials.top-navigation')

    @yield('content')

    @include('clients.admin.partials.footer')
      </div>
    </div>

    <!-- jQuery -->
    <script src="{{asset('assets/clients/admin/vendors/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{asset('assets/clients/admin/vendors/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{asset('assets/clients/admin/vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- NProgress -->
    <script src="{{asset('assets/clients/admin/vendors/nprogress/nprogress.js')}}"></script>
    <!-- Chart.js -->
    <script src="{{asset('assets/clients/admin/vendors/Chart.js/dist/Chart.min.js')}}"></script>
    <!-- gauge.js -->
    <script src="{{asset('assets/clients/admin/vendors/gauge.js/dist/gauge.min.js')}}"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{asset('assets/clients/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
    <!-- iCheck -->
    <script src="{{asset('assets/clients/admin/vendors/iCheck/icheck.min.js')}}"></script>
    <!-- Skycons -->
    <script src="{{asset('assets/clients/admin/vendors/skycons/skycons.js')}}"></script>
    <!-- Flot -->
    <script src="{{asset('assets/clients/admin/vendors/Flot/jquery.flot.js')}}"></script>
    <script src="{{asset('assets/clients/admin/vendors/Flot/jquery.flot.pie.js')}}"></script>
    <script src="{{asset('assets/clients/admin/vendors/Flot/jquery.flot.time.js')}}"></script>
    <script src="{{asset('assets/clients/admin/vendors/Flot/jquery.flot.stack.js')}}"></script>
    <script src="{{asset('assets/clients/admin/vendors/Flot/jquery.flot.resize.js')}}"></script>
    <!-- Flot plugins -->
    <script src="{{asset('assets/clients/admin/vendors/flot.orderbars/js/jquery.flot.orderBars.js')}}"></script>
    <script src="{{asset('assets/clients/admin/vendors/flot-spline/js/jquery.flot.spline.min.js')}}"></script>
    <script src="{{asset('assets/clients/admin/vendors/flot.curvedlines/curvedLines.js')}}"></script>
    <!-- DateJS -->
    <script src="{{asset('assets/clients/admin/vendors/DateJS/build/date.js')}}"></script>
    <!-- JQVMap -->
    <script src="{{asset('assets/clients/admin/vendors/jqvmap/dist/jquery.vmap.js')}}"></script>
    <script src="{{asset('assets/clients/admin/vendors/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
    <script src="{{asset('assets/clients/admin/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js')}}"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{asset('assets/clients/admin/vendors/moment/min/moment.min.js')}}"></script>
    <script src="{{asset('assets/clients/admin/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <!-- Custom Theme Scripts -->
    <script src="{{asset('assets/clients/admin/build/js/custom.min.js')}}"></script>

    <script>
      (function () {
        const container = document.getElementById('simple-toast-container');
        if (!container) return;

        const flashError = JSON.parse(document.querySelector('meta[name="flash-error"]')?.content ?? 'null');
        const flashSuccess = JSON.parse(document.querySelector('meta[name="flash-success"]')?.content ?? 'null');

        function iconSvg(type) {
          if (type === 'success') {
            return '<svg style="width:22px;height:22px;flex:0 0 22px;margin-top:2px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.2 16.2L5.7 12.7L4.3 14.1L9.2 19L20 8.2L18.6 6.8L9.2 16.2Z" fill="white"/></svg>';
          }
          return '<svg style="width:22px;height:22px;flex:0 0 22px;margin-top:2px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2Zm1 15h-2v-2h2v2Zm0-4h-2V7h2v6Z" fill="white"/></svg>';
        }

        function toast(type, message) {
          if (!message) return;
          const title = type === 'success' ? 'Success' : 'Error';
          const bg = type === 'success' ? '#2ecc71' : '#c0392b';
          const el = document.createElement('div');
          el.style.cssText = 'width:320px;color:#fff;padding:14px 16px;border-radius:4px;box-shadow:0 8px 18px rgba(0,0,0,.22);display:flex;gap:12px;align-items:flex-start;pointer-events:auto;background:' + bg;
          el.innerHTML = iconSvg(type) + '<div><div style="font-weight:700;line-height:1.1">' + title + '</div><div style="margin-top:4px;line-height:1.2">' + String(message) + '</div></div>';
          container.appendChild(el);
          setTimeout(() => { el.remove(); }, 3500);
        }

        if (flashError) toast('error', flashError);
        if (flashSuccess) toast('success', flashSuccess);
      })();
    </script>

    <script>
      (function () {
        window.__adminLogout = function (event) {
          if (event) event.preventDefault();
          const form = document.getElementById('admin-logout-form');
          if (form) form.submit();
        }
      })();
    </script>
	
  </body>
</html>
