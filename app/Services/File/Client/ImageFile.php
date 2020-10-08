<?php

namespace App\Services\File\Client;

use App\Services\File\Interfaces\FileInterface;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;


/**
 * Created by PhpStorm.
 * User: apakula
 * Date: 10.11.17
 * Time: 12:52
 */
class ImageFile implements FileInterface
{

    /**
     * @var Image $img
     */
    private $img;

    /**
     * @var UploadedFile $img
     */
    private $file;

    private $unicornName;

    private $parentFilename;


    /**
     * @param UploadedFile $file File: $request->file('image')
     * @return array
     */
    public function getMetaData(UploadedFile $file)
    {
        $this->file = $file;

        $data = [
            'extension' => $this->getExtension(),
            'size' => $this->getSize(),
            'file_name' => $this->getName(),
            'mime' => $this->getMime()
        ];

        if ($file->getExtension() != 'svg') {
            $this->img = Image::make($file->getPathname());
            $data['exif'] = $this->getExif();
            $data['iptc'] = $this->getIptc();
        }

        return $data;

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

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->img->width();
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->img->height();
    }

    /**
     * @return array
     */
    public function getExif()
    {
        $exif = $this->img->exif();
        if (!is_array($exif)) {
            return [];
        }
        $response = [];

        $allowedExifKeys = [
            'FileDateTime',
            'FileType',
            'SectionsFound',
            'ImageDescription',
            'Artist',
            'Copyright',
            'Exif_IFD_Pointer',
            'Title',
            'Author',
        ];

        foreach ($allowedExifKeys as $field) {
            if (key_exists($field, $exif)) {
                $response[$field] = $exif[$field];
            }
        }

        return $response;
    }

    /**
     * @return array
     */
    public function getIptc()
    {
        return $this->img->iptc();
    }

    /**
     * @param $dbImg
     * @param array $paramsIn contains offset x, offset y, width, height
     */
    public function crop($dbImg, $params)
    {
        $this->img = Image::make(storage_path('app/public/' . $dbImg->file));
        $this->parentFilename = $this->img->filename;

        if ($params['x'] == 0 || $params['y'] == 0 || $params['x'] < 650 || $params['y'] < 650) {
            $params['x'] = $params['y'] = ($this->img->height() > $this->img->width()) ? $this->img->width() : $this->img->height();

            $params['offset_x'] = ($this->img->width() - $params['x']);
            $params['offset_y'] = 0;
        }

        $this->img->crop(
            floor($params['x']),
            floor($params['y']),
            floor($params['offset_x']),
            floor($params['offset_y'])
        );

        $this->unicornName = '/tmp/' . str_random(16) . '.' . $this->img->extension;
        $this->img->save($this->unicornName);
    }


    public function getImage()
    {
        return $this->img;
    }

    /*
     * Gdzieś na świecie płacze jeden jednorożec Taylor'a
     * wyjaśnienie: w jaki sposób zapisać pllik z użyciem FileService
     * Tailorowe rozwiązanie: Trzeba zapisać plik tymczasowy z super nazwą i następnie
     * użyć tej nazwy pliku w FileService, po czym plik tymczasowy usuwamy.
     */
    public function destroyImg()
    {
        if (method_exists($this->img, 'destroy')) {
            $this->img->destroy();
            unlink($this->unicornName);
        }
    }

    public function getTemporaryFile()
    {
        return $this->unicornName;
    }

    public function getParentFilename()
    {
        return $this->parentFilename;
    }

}