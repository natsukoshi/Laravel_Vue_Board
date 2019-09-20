<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    /**
     * モデルの配列形態に追加するアクセサ
     *
     * @var array
     */
    protected $appends = ['file_url'];



    /**
     * deleteをオーバーライドしている。画像の実ファイルを削除した後に、元のメソッドを呼ぶ。
     * @return void
     */
    public function delete()
    {
        // Storage::delete(['file', 'otherFile']);($this->file_name);
        Storage::disk('local')->delete(config("const.IMAGE_SAVE_PATH") . $this->file_name);
        $this->delete();
    }

    /**
     * 画像のパスを取得
     *
     * @return string
     */
    public function getFileUrlAttribute()
    {
        return "/storage" . "/img/" . $this->file_name;
    }

    /**
     * 画像にユニークなファイル名を保存し、モデルをDBにも格納
     * @param Illuminate\Http\UploadedFile $file
     * @return void
     */
    public function saveImage(\Illuminate\Http\UploadedFile $file)
    {
        // todo エラー時にExceptionを投げる

        \Log::channel('single')->debug("Imageモデル：ファイル保存する前");

        //ユニークなファイル名を作成
        $imgFileName = \uniqid("img_") . '.' . $file->extension();

        // ファイルを保存

        $file->move(config("const.IMAGE_SAVE_PATH"), $imgFileName);
        // $file->storeAs(config("const.IMAGE_SAVE_PATH"), $imgFileName);

        \Log::channel('single')->debug("Imageモデル：ファイル保存した後");

        // $img = new App\Image;
        $this->file_name = $imgFileName;
        $this->save();

        \Log::channel('single')->debug("Imageモデル：DBに保存した後");
    }
}