<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;

class Reply extends Post
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
     * リレーションシップ - postsテーブル　返信元の投稿を取得する
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function parentPost()
    {
        return $this->belongsTo('App\Post', 'parent_post_id', 'id');
                        // ('対象のモデル', '子の外部キー', '親のモデルの主キー')
    }


}
