<?php

use Hashids\Hashids;
use Illuminate\Support\Facades\Storage;

if (!function_exists('encode_id')) {

    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function encode_id($id)
    {
        $hash = new Hashids('blog', 10);
        return $hash->encode($id);
    }
}

if (!function_exists('decode_id')) {

    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function decode_id($hash_id)
    {
        $hash = new Hashids('blog', 10);
        $id = $hash->decode($hash_id);
        if (!empty($id) && isset($id[0])){
            return $id[0];
        }
        return '';
    }
}

if (!function_exists('check_url')) {

    function check_url($url)
    {
        if (!preg_match("/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/",
            $url)) {
            return false;
        }
        return true;
    }

}


if (!function_exists('format_image')) {
    function format_image($image) {
        if (check_url($image)) {
            return $image;
        }
        if ($image && Storage::disk('public')->exists($image)) return Storage::disk('public')->url($image);;
        return '';
    }
}
