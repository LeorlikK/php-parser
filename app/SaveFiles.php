<?php

namespace App;

use Masterminds\HTML5\Exception;
use stdClass;

class SaveFiles
{
    public function saveFile(string|array $content, string $dir = '', string $name = 'parser', string $format = 'json'):bool
    {
        try {
            if (!is_dir(PROJECT_DIR . $dir)){
                mkdir(PROJECT_DIR . $dir, recursive:true);
            }

            $file = fopen(PROJECT_DIR . $dir . '/' . $name . '.' . $format, 'w');

            if ($format == 'json'){
                if (is_array($content)){
                    $content = $this->jsonEncode($content);
                }
                fwrite($file, $content);
            }

            if ($format == 'txt'){
                foreach ($content as $key => $value) {
                    $value .= "\r\n";
                    fwrite($file, $value);
                }
            }
            fclose($file);

        }catch (\Exception $exception){
            return throw new \Exception('error save file');
        }

        return true;
    }

    public function openFile(string $string, bool $decode = false)
    {
        try {
            $filename = PROJECT_DIR . $string;
            $file = fopen($filename, 'r');
            $content = fread($file, filesize($filename));
            fclose($file);

        }catch (\Exception $exception){
            return throw new \Exception('error open file');
        }

        if ($decode){
            $content = $this->jsonDecode($content);
        }
        return $content;
    }

    public function jsonEncode($content):string
    {
        return json_encode($content, JSON_UNESCAPED_UNICODE);
    }

    public function jsonDecode($content):stdClass|array
    {
        return json_decode($content, flags:JSON_UNESCAPED_UNICODE);
    }

    public function saveImg(string $url):bool
    {
        try {
            $img = file_get_contents($url);
            $imgName = explode('.', $url);
            $format = array_pop($imgName);
            $imgName = end($imgName);
            $str = $imgName . '.' . $format;
            $str = str_replace('/', '-', $str);
            $file = fopen("../storage/$str" , 'w');
            fwrite($file, $img);
            fclose($file);
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}