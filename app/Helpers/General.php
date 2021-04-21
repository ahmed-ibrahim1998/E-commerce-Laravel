<?php

use App\Models\Language;
use Illuminate\Support\Facades\Config;

function get_language() {
    //\App\Models\Language::where('active',1)->select('abbr','local','name','direction','active')-get();
        // Or
    return Language::active()->Selection() -> get();

}

function get_default_lang() {
    return config::get('app.locale');  // To Show Default Language To Admin
}

function uploadImage($folder, $image)
{
    $image->store('/', $folder);
    $filename = $image -> hashName();
    $path = 'images/' . $folder . '/' . $filename;
    return $path;
}


//function uploadVideo($folder, $video)
//{
//    $video->store('/', $folder);
//    $filename = $video->hashName();
//    $path = 'video/' . $folder . '/' . $filename;
//    return $path;
//}


