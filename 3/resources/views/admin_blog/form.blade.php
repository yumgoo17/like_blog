<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ブログ記事投稿フォーム</title>
    {{--asset ヘルパー関数を使うと public/ 配下ファイルへのURLを生成してくれる--}}
    {{--bootstrap.min.css は最初からインストールされている--}}
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/blog.css') }}">
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h2>ブログ記事投稿・編集</h2>

            {{--if 文による条件分岐--}}
            @if (session('message'))
                <div class="alert alert-success">
                    {{--セッションヘルパーを使ってキーを指定して、セッションに保存されたデータを取り出す--}}
                    {{ session('message') }}
                </div>
                <br>
            @endif

            {{--$errors は Illuminate\Support\MessageBag インスタンスで、エラーメッセージの操作に便利なメソッドを使うことができる--}}
            {{--バリデートエラーがあった場合は、自動的にエラー内容・メッセージが保存された状態で、元のアドレスにリダイレクトされる--}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        {{--foreach 文によるループ--}}
                        {{--エラーメッセージがあるなら、それを全て取り出して表示--}}
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{--変数を {{  }} で囲うと変数の内容が表示される。{{  }} の中にはPHPの関数を書くこともできる--}}
            {{--{{  }} で囲われると自動的に htmlspecialchars 関数が通されエスケープされる--}}
            <form method="POST" action="{{ route('admin_post') }}">
                <div class="form-group">
                    <label>日付</label>
                    <input class="form-control" name="post_date" size="20" value="" placeholder="日付を入力して下さい。">
                </div>

                <div class="form-group">
                    <label>タイトル</label>
                    <input class="form-control" name="title" value="" placeholder="タイトルを入力して下さい。">
                </div>

                <div class="form-group">
                    <label>本文</label>
                    <textarea class="form-control" rows="15" name="body" placeholder="本文を入力してください。"></textarea>
                </div>

                <input type="submit" class="btn btn-primary btn-sm" value="送信">
                {{--CSRFトークンが生成される--}}
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</div>

</body>
</html>
