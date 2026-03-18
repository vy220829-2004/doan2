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
    <meta name="validation-errors" content='@json($errors->any() ? $errors->all() : [])'>

    <title>Đăng nhập Admin</title>

    <!-- Bootstrap -->
    <link href="{{ asset('assets/clients/admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/clients/admin/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('assets/clients/admin/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ asset('assets/clients/admin/vendors/animate.css/animate.min.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('assets/clients/admin/build/css/custom.min.css') }}" rel="stylesheet">

    <style>
        .login_wrapper {
            max-width: 420px;
            width: 100%;
        }

        .simple-toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .simple-toast {
            width: 320px;
            color: #fff;
            padding: 14px 16px;
            border-radius: 4px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.22);
            display: flex;
            gap: 12px;
            align-items: flex-start;
            pointer-events: auto;
        }

        .simple-toast--error { background: #c0392b; }
        .simple-toast--success { background: #2ecc71; }

        .simple-toast__icon {
            width: 22px;
            height: 22px;
            flex: 0 0 22px;
            margin-top: 2px;
        }

        .simple-toast__title { font-weight: 700; line-height: 1.1; }
        .simple-toast__message { margin-top: 4px; line-height: 1.2; }
    </style>
</head>

<body class="login">
    <div class="simple-toast-container" id="simple-toast-container"></div>
    <div>
        <a class="hiddenanchor" id="signin"></a>

        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <h1>Đăng nhập</h1>

                    <form action="{{ route('admin.login.post') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <input type="email" name="email" class="form-control input-lg" placeholder="Email" required />
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" class="form-control input-lg" placeholder="Mật khẩu" required />
                        </div>

                        <div>
                            <button class="btn btn-primary btn-lg btn-block" type="submit">Đăng nhập</button>
                        </div>
                    </form>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <div class="clearfix"></div>
                        <br />

                        <div>
                            <h1><i class="fa fa-paw"></i> clean food</h1>
                            <p>©2026 All Rights Reserved.</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>

<script>
    (function () {
        const container = document.getElementById('simple-toast-container');
        if (!container) return;

        const flashError = JSON.parse(document.querySelector('meta[name="flash-error"]')?.content ?? 'null');
        const flashSuccess = JSON.parse(document.querySelector('meta[name="flash-success"]')?.content ?? 'null');
        const validationErrors = JSON.parse(document.querySelector('meta[name="validation-errors"]')?.content ?? '[]');

        function iconSvg(type) {
            if (type === 'success') {
                return '<svg class="simple-toast__icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.2 16.2L5.7 12.7L4.3 14.1L9.2 19L20 8.2L18.6 6.8L9.2 16.2Z" fill="white"/></svg>';
            }
            return '<svg class="simple-toast__icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2Zm1 15h-2v-2h2v2Zm0-4h-2V7h2v6Z" fill="white"/></svg>';
        }

        function toast(type, message) {
            if (!message) return;
            const title = type === 'success' ? 'Success' : 'Error';
            const el = document.createElement('div');
            el.className = 'simple-toast simple-toast--' + (type === 'success' ? 'success' : 'error');
            el.innerHTML = iconSvg(type) +
                '<div><div class="simple-toast__title">' + title +
                '</div><div class="simple-toast__message">' + String(message) + '</div></div>';
            container.appendChild(el);
            setTimeout(() => { el.remove(); }, 3500);
        }

        if (flashError) toast('error', flashError);
        if (flashSuccess) toast('success', flashSuccess);
        if (Array.isArray(validationErrors)) {
            validationErrors.forEach((m) => toast('error', m));
        }
    })();
</script>

</html>