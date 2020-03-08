<?php

namespace SfpPHPExtension;

/**
 * @deprecated 
 */
class PeclData
{
    private $jsonDir;

    public function __construct($json_dir)
    {
        $this->jsonDir = $json_dir;
    }

    public function has($key)
    {
        $ext_name = str_replace('/', '_', $key);
        return file_exists($this->jsonDir.DIRECTORY_SEPARATOR."{$ext_name}.json");
    }

    public function get($key)
    {
        $ext_name = str_replace('/', '_', $key);
        $data =  json_decode(file_get_contents($this->jsonDir.DIRECTORY_SEPARATOR."{$ext_name}.json"));

        $data->Maintainers = str_replace(['[details]', ' (lead)', '(developer)', '[wishlist]'], '' , $data->Maintainers);
        $data->Maintainers = preg_replace('/\w+@\w+\.\w+/s', '' , $data->Maintainers);
        $data->Maintainers = str_replace(['&lt;','&gt;',], '' , $data->Maintainers);

//        $data->Maintainers = preg_replace('/\&lt\;.*\&gt\;/s', ',' , $data->Maintainers);

        return $data;
    }
}