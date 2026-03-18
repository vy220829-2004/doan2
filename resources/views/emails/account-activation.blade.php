<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kích hoạt tài khoản</title>
</head>

<body>
    <p>Xin chào {{ $user->name }},</p>

    <p>Bạn đã đăng ký tài khoản tại hệ thống. Vui lòng bấm vào link dưới đây để kích hoạt tài khoản:</p>

    <p>
        <a href="{{ $activationUrl }}">Kích hoạt tài khoản</a>
    </p>

    <p>Nếu bạn không thực hiện đăng ký, vui lòng bỏ qua email này.</p>

    <p>Trân trọng.</p>
</body>

</html>
