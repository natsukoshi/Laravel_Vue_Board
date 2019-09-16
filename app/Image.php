<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * 画像にユニークなファイル名を保存し、モデルをDBにも格納
     * @param Illuminate\Http\UploadedFile $file
     * @return void
     */
    public function saveImage(\Illuminate\Http\UploadedFile $file)
    {
        // todo エラー時にExceptionを投げる
        if (!$file->isValid()) return null;

        \Log::channel('single')->debug("Imageモデル：ファイル保存する前");


        //ユニークなファイル名を作成
        $imgFileName = \uniqid("img_") . '.' . $file->extension();

        // ファイルを保存
        $file->move(public_path() . "/img/", $imgFileName);

        \Log::channel('single')->debug("Imageモデル：ファイル保存した後");

        // $img = new App\Image;
        $this->file_name = $imgFileName;

        $this->save();

        \Log::channel('single')->debug("Imageモデル：DBに保存した後");
    }
}
