<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageLib;
use Illuminate\Support\Facades\DB;


class Image extends Model
{
    // private const ENV = env('APP_ENV') === 'local' ? 'local' : 's3';

    /**
     * モデルの配列形態に追加するアクセサ
     *
     * @var array
     */
    protected $appends = ['file_url'];


    /**
     * 画像のパスを取得
     *
     * @return string
     */
    public function getFileUrlAttribute()
    {
        // return "/storage" . "/img/" . $this->file_name; //Local
        return Storage::disk('s3')->url($this->file_name); //AWS S3
    }


    /**
     * 画像の実ファイルを削除する
     * @return void
     */
    public function deleteImageFile()
    {
        $result = Storage::disk('s3')->delete($this->file_name);
        // $result = Storage::disk('local')->delete("/public/img/" . $this->file_name);

        $result ? \Log::channel('single')->debug("画像削除完了") : \Log::channel('single')->debug("画像削除できませんでした");
    }


    /**
     * 画像にユニークなファイル名をつけて保存し、モデルをDBにも格納
     * @param Illuminate\Http\UploadedFile $file
     * @return void
     */
    public function saveImage(\Illuminate\Http\UploadedFile $file)
    {
        // todo エラー時にExceptionを投げる

        \Log::channel('errorlog')->debug("Imageモデル：ファイルをAWSに保存する前");

        //ユニークなファイル名を作成
        $imgFileName = \uniqid("img_") . '.' . $file->extension();

        // 画像サイズが大きすぎる場合比率を保って縮小
        // $image = $this->downsizeImage($file);
        // $image->save(config("const.IMAGE_SAVE_PATH") . $imgFileName);

        // S3にファイルを保存する
        // 第三引数の'public'はファイルを公開状態で保存するため
        Storage::cloud()->putFileAs('', $file, $imgFileName, 'public');
        \Log::channel('errorlog')->debug("Imageモデル：ファイル保存した後" . $imgFileName);

        // データベースエラー時にファイル削除を行うため
        // トランザクションを利用する
        DB::beginTransaction();

        try {
            $this->file_name = $imgFileName;
            $this->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            // DBとの不整合を避けるためアップロードしたファイルを削除
            Storage::cloud()->delete($imgFileName);
            throw $exception;
        }

        $image = $this->downsizeImage($file);


        \Log::channel('errorlog')->debug("Imageモデル：DBに保存した後");
    }

    // /**
    //  * 画像にユニークなファイル名を保存し、モデルをDBにも格納
    //  * @param Illuminate\Http\UploadedFile $file
    //  * @return void
    //  */
    // public function saveImage(\Illuminate\Http\UploadedFile $file)
    // {
    //     // todo エラー時にExceptionを投げる

    //     \Log::channel('errorlog')->debug("Imageモデル：ファイル保存する前");

    //     //ユニークなファイル名を作成
    //     $imgFileName = \uniqid("img_") . '.' . $file->extension();

    //     // 画像サイズが大きすぎる場合比率を保って縮小
    //     // $image = $this->downsizeImage($file);
    //     // $image->save(config("const.IMAGE_SAVE_PATH") . $imgFileName);

    //     // ファイルを保存
    //     // $file->move(config("const.IMAGE_SAVE_PATH"), $imgFileName);
    //     $isSuccses = $file->storeAs(config("const.IMAGE_SAVE_PATH"), $imgFileName);
    //     if ($isSuccses === false) {
    //         \Log::channel('errorlog')->debug("ファイル保存失敗" . $imgFileName);
    //     } else {
    //         \Log::channel('errorlog')->debug("ファイル保存成功" . $isSuccses);
    //     }

    //     \Log::channel('errorlog')->debug("Imageモデル：ファイル保存した後" . $imgFileName);

    //     // $img = new App\Image;
    //     $this->file_name = $imgFileName;
    //     $this->save();

    //     \Log::channel('errorlog')->debug("Imageモデル：DBに保存した後");
    // }

    /**
     * 画像が規定のサイズより大きい場合比率を保って縮小する。
     * Interventionの画像形式で返却
     *
     * @param Illuminate\Http\UploadedFile $file
     * @return Intervention\Image\Facades\Image
     */
    public function downsizeImage(\Illuminate\Http\UploadedFile $file)
    {
        $image = ImageLib::make($file);
        $size = [
            "w" => $image->width(),
            "h" => $image->height()
        ];

        $moreLarge = $size["w"] >= $size["h"] ? "w" : "h";

        //長辺に合わせて縦横比を保って縮小
        if ($size[$moreLarge] > 500) {
            if ($moreLarge === "w") {
                $image->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $image->resize(null, 500, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        return $image;
    }
}
