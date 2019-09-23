<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

// use App\Events\ImageDeleting;
use Illuminate\Notifications\Notifiable;

class Image extends Model
{
    // use Notifiable;
    // /**
    //  * モデルのイベントマップ
    //  *
    //  * @var array
    //  */
    // protected $dispatchesEvents = [
    //     'deleting' => ImageDeleting::class,
    // ];


    /**
     * モデルの配列形態に追加するアクセサ
     *
     * @var array
     */
    protected $appends = ['file_url'];




    /**
     * 画像の実ファイルを削除する
     * @return void
     */
    public function deleteImageFile()
    {
        // Storage::delete(['file', 'otherFile']);($this->file_name);
        // $result = Storage::disk('local')->delete(config("const.IMAGE_SAVE_PATH") . $this->file_name);
        $result = Storage::disk('local')->delete("/public/img/" . $this->file_name);

        $result ? \Log::channel('single')->debug("画像削除完了") : \Log::channel('single')->debug("画像削除できませんでした");
        //$this->delete();
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

        \Log::channel('single')->debug("Imageモデル：ファイル保存した後" . $imgFileName);

        // $img = new App\Image;
        $this->file_name = $imgFileName;
        $this->save();

        \Log::channel('single')->debug("Imageモデル：DBに保存した後");
    }
}
