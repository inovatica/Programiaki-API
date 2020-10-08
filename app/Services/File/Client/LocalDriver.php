<?php
/**
 * Created by PhpStorm.
 * User: apakula
 * Date: 10.11.17
 * Time: 14:15
 */

namespace App\Services\File\Client;


use App\Services\File\Interfaces\DriverInterface;
use Illuminate\Http\UploadedFile;

class LocalDriver implements DriverInterface
{
    const DRIVER_NAME = 'local';

    private $errors = [];

    public function getDriverName()
    {
        return self::DRIVER_NAME;
    }

    /**
     * @param UploadedFile $file Image file: $request->file('image')
     * @param $path string
     * @param $fileName string
     * @return bool
     */
    public function save($file, $path, $fileName)
    {
        return \Storage::putFileAs($path, $file, $fileName);
    }

    public function remove()
    {
        // TODO: Implement remove() method.
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }


}