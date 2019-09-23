<?php

namespace App\Observers;

use App\Image;

class ImageObserver
{
    /**
     * Handle the image "created" event.
     *
     * @param  \App\Image  $image
     * @return void
     */
    public function created(Image $image)
    {
        //
    }

    /**
     * Handle the image "updated" event.
     *
     * @param  \App\Image  $image
     * @return void
     */
    public function updated(Image $image)
    {
        //
    }

    /**
     * Handle the image "deleted" event.
     *
     * @param  \App\Image  $image
     * @return void
     */
    public function deleted(Image $image)
    {
        $image->deleteImageFile();
        \Log::channel('single')->debug("ImageObserverのdeleted呼ばれた");
    }

    // /**
    //  * Handle the image "deleting" event.
    //  * Imageのレコード削除時に画像ファイルも削除する
    //  *
    //  * @param  \App\Image  $image
    //  * @return void
    //  */
    // public function deleting(Image $image)
    // {
    //     $image->deleteImageFile();
    // }

    /**
     * Handle the image "restored" event.
     *
     * @param  \App\Image  $image
     * @return void
     */
    public function restored(Image $image)
    {
        //
    }

    /**
     * Handle the image "force deleted" event.
     *
     * @param  \App\Image  $image
     * @return void
     */
    public function forceDeleted(Image $image)
    {
        //
    }
}
