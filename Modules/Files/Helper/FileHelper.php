<?php

namespace Modules\Files\Helper;

use Illuminate\Support\Facades\File;
use Modules\Files\Entities\Files;

/**
 * Class FileHelper
 * @package Modules\Files\Helper
 */
class FileHelper
{
    /**
     * @param $dataBase64
     * @param $folder
     * @param string $disk
     * @param string $file_name
     * @return array
     */
    public static function uploadDataRecordBase64($dataBase64, $folder, $disk = 'public', $file_name = "")
    {
        $data = base64_decode($dataBase64);
        $data = str_replace(' ', '+', $data);

        $base_path = base_path() . DIRECTORY_SEPARATOR . $disk;
        $imageName = $file_name ? $file_name : md5(time()); // generating unique file name;

        $dir_original = implode(DIRECTORY_SEPARATOR, [$base_path, $folder]);

        if (!File::exists($dir_original)) {
            File::makeDirectory($dir_original, 0777, true);
        }
        // save image to original path
        $path_original = $dir_original . DIRECTORY_SEPARATOR . $imageName;
        file_put_contents($path_original, $data);

        return [
            'name' => $imageName,
            'path' => str_replace($base_path, '', $path_original),
            'path_thumbnail' => '',
        ];
    }

    /**
     * @param $file
     * @param $folder
     * @param string $disk
     * @param string $file_name
     * @return array
     */
    public static function uploadFile($file, $folder, $disk = 'public', $file_name = "")
    {
        $base_path = base_path() . DIRECTORY_SEPARATOR . $disk;
        $date_folder = date("Ym");
        $imageName = $file_name ?: date("YmdHis") . '_' . $file->getClientOriginalName();
        $nameDisplay = $file->getClientOriginalName();
        $dir_original = implode(DIRECTORY_SEPARATOR, [$base_path, $folder, $date_folder]);

        if (!File::exists($dir_original)) {
            File::makeDirectory($dir_original, 0777, true);
        }
        $file->move($dir_original, $imageName);
        $path_original = $dir_original . DIRECTORY_SEPARATOR . $imageName;
        return [
            'name' => $nameDisplay,
            'path' => str_replace($base_path, '', $path_original),
            'path_thumbnail' => '',
        ];
    }

    /**
     * Save image in database
     *
     * @param  $image_data
     * @param  $id
     * @param  $class
     * @return mixed
     */
    public static function saveFile($image_data, $id = null, $class = null)
    {
        $image_data['fileable_id'] = $id;
        $image_data['fileable_type'] = $class;
        $image = new Files($image_data);
        $image->save();

        return $image;
    }


}
