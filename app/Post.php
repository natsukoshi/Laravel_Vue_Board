<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * リレーションシップ - usersテーブル　投稿の投稿者を取得する
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
                        // ('対象のモデル', '子の外部キー', '親のモデルの主キー')
    }

    /**
     * リレーションシップ - replysテーブル　投稿についている返信を取得する
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function reply()
    {
        return $this->hasMany('App\Reply', 'parent_id', 'id');
                        // ('対象のモデル', '子の外部キー', '親のモデルの主キー')
    }
}
