<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    // SoftDeletes トレイトを使う
    use SoftDeletes;

    // 対象テーブルのプライマリキーのカラム名を指定する。デフォルトは 'id' というカラム名が想定されている。
    protected $primaryKey = 'article_id';

    // 「複数代入」を利用するときに指定する。追加・編集可能なカラム名のみを指定する。
    // $guarded プロパティを利用すると、逆に、追加・編集不可能なカラムを指定できる。
    protected $fillable = ['category_id', 'post_date', 'title', 'body'];

    // $dates プロパティには、日時が入るカラムを設定する（日付ミューテタ）
    // そうすると、その値が自動的に Carbon インスタンスに変換される
    protected $dates = ['post_date', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Category モデルのリレーション
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        // 記事は1つのカテゴリーと関係しているので、hasOne メソッドを利用する
        // 第一引数は関係するモデルの名前で、第二・第三引数は外部キーです
        return $this->hasOne('App\Models\Category', 'category_id', 'category_id');
    }

    /**
     * 記事リストを取得する
     *
     * @param  int   $num_per_page 1ページ当たりの表示件数
     * @param  array $condition    検索条件
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getArticleList(int $num_per_page = 10, array $condition = [])
    {
        // パラメータの取得
        $category_id = array_get($condition, 'category_id');
        $year        = array_get($condition, 'year');
        $month       = array_get($condition, 'month');

        // Eager ロードの設定を追加
        $query = $this->with('category')->orderBy('article_id', 'desc');

        // カテゴリーIDの指定
        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        // 期間の指定
        if ($year) {
            if ($month) {
                // 月の指定がある場合はその月を設定し、Carbonインスタンスを生成
                $start_date = Carbon::createFromDate($year, $month, 1);
                $end_date   = Carbon::createFromDate($year, $month, 1)->addMonth();     // 1ヶ月後
            } else {
                // 月の指定が無い場合は1月に設定し、Carbonインスタンスを生成
                $start_date = Carbon::createFromDate($year, 1, 1);
                $end_date   = Carbon::createFromDate($year, 1, 1)->addYear();           // 1年後
            }
            // Where句を追加
            $query->where('post_date', '>=', $start_date->format('Y-m-d'))
                  ->where('post_date', '<',  $end_date->format('Y-m-d'));
        }

        // paginate メソッドを使うと、ページネーションに必要な全件数やオフセットの指定などは全部やってくれる
        return $query->paginate($num_per_page);
    }

    /**
     * 月別アーカイブの対象月のリストを取得
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getMonthList()
    {
        // selectRaw メソッドを使うと、引数にSELECT文の中身を書いてそのまま実行できる
        // 返り値はコレクション（Illuminate\Database\Eloquent\Collection Object）
        // コレクションとは配列データを操作するための便利なラッパーで、多種多様なメソッドが用意されている
        $month_list = $this->selectRaw('substring(post_date, 1, 7) AS year_and_month')
            ->groupBy('year_and_month')
            ->orderBy('year_and_month', 'desc')
            ->get();

        foreach ($month_list as $value) {
            // YYYY-MM をハイフンで分解して、YYYY年MM月という表記を作る
            list($year, $month) = explode('-', $value->year_and_month);
            $value->year  = $year;
            $value->month = (int)$month;
            $value->year_month = sprintf("%04d年%02d月", $year, $month);
        }
        return $month_list;
    }

    /**
     * post_date のアクセサ YYYY/MM/DD のフォーマットにする
     *
     * @return string
     */
    public function getPostDateTextAttribute()
    {
        // アクセサを定義しておくと $article->post_date_text という
        // プロパティにアクセスしたときに、このメソッドの返り値が返る
        // 'post_date' は $dates プロパティに設定してあるので、自動的に Carbon インスタンスとなる
        return $this->post_date->format('Y/m/d');
    }

    /**
     * post_date のミューテタ YYYY-MM-DD のフォーマットでセットする
     *
     * @param $value
     */
    public function setPostDateAttribute($value)
    {
        // ミューテタはプロパティに設定しようとする値を受け取って加工する
        // そして加工したものを Eloquent モデルの $attributes プロパティに設定する
        // 例えば $article->post_date = '2018/07/07' とすると、
        // このメソッドが自動的に呼び出され、引数 $value には '2018/07/07' が渡される
        // 今回はDBに入れることのできる YYYY-MM-DD のフォーマットにする
        $post_date = new Carbon($value);
        $this->attributes['post_date'] = $post_date->format('Y-m-d');
    }
}
