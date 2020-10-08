<?php
/**
 * Created by PhpStorm.
 * User: apakula
 * Date: 10.11.17
 * Time: 11:11
 */

namespace App\Services\File\Interfaces;


use Illuminate\Http\UploadedFile;

interface FileInterface
{
    /**
     * @param UploadedFile $file File: $request->file('image')
     * @return array
     */
    public function getMetaData(UploadedFile $file);

    /**
     * @return string
     */
    public function getExtension();

    /**
     * @return int
     */
    public function getSize();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getMime();

}