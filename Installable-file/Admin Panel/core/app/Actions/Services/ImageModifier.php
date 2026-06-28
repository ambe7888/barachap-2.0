<?php

namespace App\Actions\Services;

class ImageModifier
{
    public static function ImageUrl($image){
        if(!empty($image)){
            return get_image_url_id_wise($image);
        }

        return null;
    }


}
