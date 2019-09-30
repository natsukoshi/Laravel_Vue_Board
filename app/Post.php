<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Reply;

class Post extends Model
{
    /**
     * モデルの配列形態に追加するアクセサ
     *
     * @var array
     */
    protected $appends = ['replies_num'];


    /**
     * 返信の数を取得
     *
     * @return string
     */
    public function getRepliesNumAttribute()
    {
        return Reply::where('parent_id', $this->id)->count();
    }

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

    /**
     * リレーションシップ - Imagesテーブル　投稿についている添付画像を取得する
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'attachment_id');
        // ('対象のモデル', '子の外部キー', '親のモデルの主キー')
    }
}
