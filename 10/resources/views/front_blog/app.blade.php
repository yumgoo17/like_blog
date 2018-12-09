<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/blog.css') }}">
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h2><a href="{{ route('front_index') }}">私のブログ</a></h2>

            {{--何らかのエラー表示用--}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{--メインカラム--}}
        @yield('main')
        {{--右サブカラム--}}
        {{--@include ディレクティブで他のテンプレートを読み込むこともできる--}}
        @include('front_blog.right_column')
    </div>
</div>
</body>
</html>
