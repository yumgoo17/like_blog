<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminBlogRequest;
use App\Models\Article;

class AdminBlogController extends Controller
{
    /** @var Article */
    protected $article;

    function __construct(Article $article)
    {
        // Article モデルクラスインスタンスの作成
        // 「依存注入」により、コンストラクタの引数にタイプヒントを指定するだけで、
        // インスタンスが生成される（コンストラクターインジェクション）
        $this->article = $article;
    }

    /**
     * ブログ記事入力フォーム
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form()
    {
        // resources/views 配下にある、どのテンプレートを使うか指定。ディレクトリの階層はピリオドで表現できる
        // この例では resources/views/admin_blog/form.blade.php が読み込まれる
        return view('admin_blog.form');
    }

    /**
     * ブログ記事保存処理
     *
     * @param AdminBlogRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(AdminBlogRequest $request)
    {
        // こちらも引数にタイプヒントを指定すると、
        // AdminBlogRequest のインスタンスが生成される（メソッドインジェクション）
        // そして、AdminBlogRequest で設定したバリデートも実行される（フォームリクエストバリデーション）

        // 入力値の取得
        $input = $request->input();

        // create メソッドで複数代入する。
        // 対象テーブルのカラム名と配列のキー名が一致する場合、一致するカラムに一致するデータが入る
        $article = $this->article->create($input);

        // リダイレクトでフォーム画面に戻る
        // route ヘルパーでリダイレクト先を指定。ルートのエイリアスを使う場合は route ヘルパーを使う
        // with メソッドで、セッションに次のリクエスト限りのデータを保存する
        return redirect()->route('admin_form')->with('message', '記事を保存しました');
    }
}
