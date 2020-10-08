<?php
/**
 * Created by PhpStorm.
 * User: apakula
 * Date: 10.11.17
 * Time: 11:11
 */

namespace App\Services\File\Interfaces;


use Illuminate\Http\UploadedFile;

interface DriverInterface
{

    public function getDriverName();

    public function getErrors();

    public function save($file, $path, $fileName);

    public function remove();

}