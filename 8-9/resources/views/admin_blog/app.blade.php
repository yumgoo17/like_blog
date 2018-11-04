<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    {{--@yield ディレクティブは指定したセクションの内容を表示するために使用する--}}
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/blog.css') }}">

    {{--app.js を読み込めば jQuery や bootstrap.js が読み込まれる--}}
    <script src="{{ asset('/js/app.js') }}"></script>
    {{--API を叩くための準備として CSRF 用トークンを設定しておく--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--各ページで <head> タグ内に追加できるようにしておく--}}
    @yield('head')
</head>

<body>
@yield('body')
</body>
</html>
