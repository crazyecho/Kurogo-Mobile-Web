<?php

class ImageLoader extends FileLoader {
    protected static function subDirectory() {
        return 'images';
    }
    
    protected static function processImageFile($inputFile, $loaderInfo) {
        // if no gd support, do nothing
        if (!function_exists('gd_info')) {
            return false;
        }
        // if there is only url and processMethod, do nothing
        if(count($loaderInfo) == 2) {
            return false;
        }
        // make inputFile as temprary file
        $outputFile = $inputFile;
        $processor = new ImageProcessor($inputFile);
        $transformer = new ImageTransformer($loaderInfo);

        //maintain original image type 
        $imageType = null;
        $processor->transform($transformer, $imageType, $outputFile);
    }

    public static function precache($url, $options) {
        $loaderInfo = array(
            'url' => $url,
            'processMethod'=>array(__CLASS__, 'processImageFile')
        );
        foreach($options as $key => $option) {
            switch($key) {
                case 'width':
                case 'height':
                case 'max_width':
                case 'max_height':
                case 'crop':
                    if($option) {
                        $loaderInfo[$key] = $option;
                    }
                    break;
            }
        }

        if (!isset($options['file'])) {
            $extension = pathinfo($url, PATHINFO_EXTENSION);
            $file = md5($url) . '.'. $extension;
        }    

        return self::generateLazyURL($file, json_encode($loaderInfo), self::subDirectory());
    }
}
