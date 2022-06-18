<!doctype html>
<html lang="fa" dir="rtl">
<head>

    @include('head-tag')
    @yield('head-tag')

</head>
<body>

<!-- start header -->
@include('header')
<!-- end header -->


<!-- start main one col -->
<main id="main-body-one-col" class="main-body">
    @yield('content')
</main>
<!-- end main one col -->


<!-- start footer -->
@include('footer')
<!-- end footer -->

</body>
</html>
