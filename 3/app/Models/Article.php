<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    // 対象テーブルのプライマリキーのカラム名を指定する。デフォルトは 'id' というカラム名が想定されている。
    protected $primaryKey = 'article_id';

    // 「複数代入」を利用するときに指定する。追加・編集可能なカラム名のみを指定する。
    // $guarded プロパティを利用すると、逆に、追加・編集不可能なカラムを指定できる。
    protected $fillable = ['post_date', 'title', 'body'];

    // $dates プロパティには、日時が入るカラムを設定する（日付ミューテタ）
    // そうすると、その値が自動的に Carbon インスタンスに変換される
    protected $dates = ['post_date', 'created_at', 'updated_at', 'deleted_at'];
}
