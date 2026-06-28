<?php

namespace App\Actions\Services;

class GalleryImageModifier
{
    public static function ImageUrl(string|null $gallery_images){
        if (is_null($gallery_images)) return null;
        $image_ids = explode('|', $gallery_images);
        $image_urls = [];
        foreach ($image_ids as $image_id) {
            $image_url = get_image_url_id_wise($image_id);
            if ($image_url) {
                $image_urls[] = $image_url;
            }
        }
        return $image_urls;
    }
}
