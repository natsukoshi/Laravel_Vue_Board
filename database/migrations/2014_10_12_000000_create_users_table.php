<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('provider_id')->nullable();  //google認証用
            $table->string('provider_name')->nullable(); //google認証用
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(); //google認証用
            $table->boolean('admin_flg')->default(false);   //管理者フラグ ture:管理者を示す
            $table->boolean('delete_flg')->default(false);  //削除フラグ　ture:削除済を示す
            $table->rememberToken();
            $table->timestamps();

            // 複合ユニークキー
            $table->unique(['provider_id', 'provider_name']); //google認証用
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }

    /**
     * リレーションシップ - postsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Post');
    }
}
