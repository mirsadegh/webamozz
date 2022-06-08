<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/font/font.css">
    <title>صفحه ورود/ ثبت نام</title>
</head>
<body>
    @yield('head-tag')
<main>

    <div class="account">
           @yield('content')
    </div>
  <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    @yield('script')
</main>
</body>
</html>
