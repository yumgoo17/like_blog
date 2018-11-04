<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryIdColumnToArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 既に存在するテーブルの更新には table メソッドを使う
        Schema::table('articles', function(Blueprint $table) {
            // after メソッドでカラムを追加する位置を指定できる（MySQLのみ）
            $table->unsignedInteger('category_id')->after('article_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function(Blueprint $table) {
            // カラムを削除するときは dropColumn メソッドを使う
            $table->dropColumn('category_id');
        });
    }
}
