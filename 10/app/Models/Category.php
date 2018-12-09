<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'category_id';
    protected $fillable = ['name', 'display_order'];
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * Article モデルのリレーション
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        // 1つのカテゴリーは多くの記事と関係しているので hasMany メソッドを利用する
        return $this->hasMany('App\Models\Article', 'category_id', 'category_id');
    }

    /**
     * カテゴリリストを取得する
     *
     * @param int    $num_per_page 1ページ当たりの表示件数
     * @param string $order        並び順の基準となるカラム
     * @param string $direction    並び順の向き asc or desc
     * @return mixed
     */
    public function getCategoryList(int $num_per_page = 0, string $order = 'display_order', string $direction = 'asc')
    {
        $query = $this->orderBy($order, $direction);
        if ($num_per_page) {
            return $query->paginate($num_per_page);
        }
        return $query->get();
    }
}
