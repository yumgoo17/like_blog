<?php

namespace App\Http\Controllers;

class AdminBlogController extends Controller
{
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
}
