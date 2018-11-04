<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrontBlogRequest;
use App\Models\Article;

class FrontBlogController extends Controller
{
    /** @var Article */
    protected $article;

    // 1ページ当たりの表示件数
    const NUM_PER_PAGE = 10;

    function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * ブログトップページ
     *
     * @param FrontBlogRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function index(FrontBlogRequest $request)
    {
        // パラメータを取得
        $input = $request->input();

        // ブログ記事一覧を取得
        $list = $this->article->getArticleList(self::NUM_PER_PAGE, $input);
        // ページネーションリンクにクエリストリングを付け加える
        $list->appends($input);
        // 月別アーカイブの対象月リストを取得
        $month_list = $this->article->getMonthList();
        return view('front_blog.index', compact('list', 'month_list'));
    }
}

