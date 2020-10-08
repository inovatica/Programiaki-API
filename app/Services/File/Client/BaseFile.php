<?php

namespace App\Services\File\Client;

use App\Services\File\FileService;
use App\Services\File\Interfaces\FileInterface;
use Barryvdh\Debugbar\Middleware\DebugbarEnabled;
use Faker\Provider\File;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use App\Models\Files;
use Intervention\Image\ImageManager;


/**
 * Created by PhpStorm.
 * User: apakula
 * Date: 10.11.17
 * Time: 12:52
 */
class BaseFile implements FileInterface
{
    /**
     * @var Image $img
     */
    private $img;

    /**
     * @var UploadedFile $img
     */
    private $file;


    /**
     * @param UploadedFile $file File: $request->file('image')
     * @return array
     */
    public function getMetaData(UploadedFile $file)
    {
        $this->file = $file;
        return [
            'extension' => $this->getExtension(),
            'size' => $this->getSize(),
            'file_name' => $this->getName(),
            'mime' => $this->getMime()
        ];

    }

    /**
     * @param UploadedFile $file File: $request->file('image')
     * @return string
     */
    public function getJsonMetaData(UploadedFile $file)
    {
        return json_decode(json_encode($this->getMetaData($file)));
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->file->getClientOriginalExtension();
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->file->getClientSize();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->file->getClientOriginalName();
    }

    /**
     * @return null|string
     */
    public function getMime()
    {
        return $this->file->getClientMimeType();
    }

}