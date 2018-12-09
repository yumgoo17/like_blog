<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminBlogRequest;
use App\Models\Article;
use App\Models\Category;

class AdminBlogController extends Controller
{
    /** @var Article */
    protected $article;
    /** @var Category */
    protected $category;

    // 1ページ当たりの表示件数
    const NUM_PER_PAGE = 10;

    function __construct(Article $article, Category $category)
    {
        $this->article = $article;
        $this->category = $category;
    }

    /**
     * ブログ記事一覧画面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list()
    {
        $list = $this->article->getArticleList(self::NUM_PER_PAGE);
        return view('admin_blog.list', compact('list'));
    }

    /**
     * ブログ記事入力フォーム
     *
     * @param  int $article_id 記事ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form(int $article_id = null)
    {
        // メソッドの引数に指定すれば、ルートパラメータを取得できる

        // Eloquent モデルはクエリビルダとしても動作するので find メソッドで記事データを取得
        // 返り値は null か App\Models\Article Object
        $article = $this->article->find($article_id);

        // 記事データがあれば toArray メソッドで配列にしておき、フォーマットした post_date を入れる
        $input = [];
        if ($article) {
            $input = $article->toArray();
            $input['post_date'] = $article->post_date_text;
        } else {
            $article_id = null;
        }

        // old ヘルパーを使うと、直前のリクエストのフラッシュデータを取得できる
        // ここではバリデートエラーとなったときに、入力していた値を old ヘルパーで取得する
        // DBから取得した値よりも優先して表示するため、array_merge の第二引数に設定する
        $input = array_merge($input, old());

        // カテゴリーの取得
        // pluck メソッドを使って引数に指定した項目で配列を生成する
        $category_list = $this->category->getCategoryList()->pluck('name', 'category_id');

        // View テンプレートへ値を渡すときは、第二引数に連想配列を設定する
        // View テンプレートでは 連想配列のキー名で値を取り出せる
//        return view('admin_blog.form', ['input' => $input, 'article_id' => $article_id]);
        // compact 関数を使うと便利
        return view('admin_blog.form', compact('input', 'article_id', 'category_list'));
    }

    /**
     * ブログ記事保存処理
     *
     * @param AdminBlogRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(AdminBlogRequest $request)
    {
        // 入力値の取得
        $input = $request->input();

        // array_get ヘルパは配列から指定されたキーの値を取り出すメソッド
        // 指定したキーが存在しない場合のデフォルト値を第三引数に設定できる
        // 指定したキーが存在しなくても、エラーにならずデフォルト値が返るのが便利
        $article_id = array_get($input, 'article_id');

        // Eloquent モデルから利用できる updateOrCreate メソッドは、第一引数の値でDBを検索し
        // レコードが見つかったら第二引数の値でそのレコードを更新、見つからなかったら新規作成します
        // ここでは article_id でレコードを検索し、第二引数の入力値でレコードを更新、または新規作成しています
        $article = $this->article->updateOrCreate(compact('article_id'), $input);

        // フォーム画面にリダイレクト。その際、route メソッドの第二引数にパラメータを指定できる
        return redirect()
            ->route('admin_form', ['article_id' => $article->article_id])
            ->with('message', '記事を保存しました');
    }

    /**
     * ブログ記事削除処理
     *
     * @param AdminBlogRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(AdminBlogRequest $request)
    {
        // 記事IDの取得
        $article_id = $request->input('article_id');

        // Article モデルを取得して delete メソッドを実行することで削除できる
        // このとき万が一 $article が null になる場合も想定して実装するのが良い（今回は紹介のみで使わないので割愛）
//        $article = $this->article->find($article_id);
//        $article->delete();

        // 主キーの値があるなら destroy メソッドで削除することができる
        // 引数は配列でも可。返り値は削除したレコード数
        $result = $this->article->destroy($article_id);
        $message = ($result) ? '記事を削除しました' : '記事の削除に失敗しました。';

        // ブログ記事一覧へリダイレクト
        return redirect()->route('admin_list')->with('message', $message);
    }

    /**
     * カテゴリ一覧画面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category()
    {
        $list = $this->category->getCategoryList(self::NUM_PER_PAGE);
        return view('admin_blog.category', compact('list'));
    }

    /**
     * カテゴリ編集・新規作成API
     *
     * @param AdminBlogRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCategory(AdminBlogRequest $request)
    {
        $input = $request->input();
        $category_id = $request->input('category_id');

        $category = $this->category->updateOrCreate(compact('category_id'), $input);

        // APIなので json のレスポンスを返す
        return response()->json($category);
    }

    /**
     * カテゴリ削除API
     *
     * @param AdminBlogRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCategory(AdminBlogRequest $request)
    {
        $category_id = $request->input('category_id');
        $this->category->destroy($category_id);

        // APIなので json のレスポンスを返す
        return response()->json();
    }
}
