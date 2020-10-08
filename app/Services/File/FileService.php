<?php

namespace App\Services\File;

use App\Models\Files;
use App\Services\File\Interfaces\DriverInterface;
use App\Services\File\Interfaces\FileInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Created by PhpStorm.
 * User: apakula
 * Date: 10.11.17
 * Time: 11:04
 */
class FileService
{

    /**
     * @var FileInterface
     */
    protected $file;

    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var array
     */
    private $errors;

    /**
     * FileService constructor.
     * @param FileInterface $file
     * @param DriverInterface $driver
     */
    public function __construct(FileInterface $file, DriverInterface $driver)
    {
        $this->file = $file;
        $this->driver = $driver;
        $this->fileModel = new Files();
    }

    /**
     * @param Model $parentModel Attach photo to this model
     * @param UploadedFile $file Image file: $request->file('image')
     * @param bool|string $fileName Optional filename, WITHOUT extension
     * @param int $parentId Parent of file
     * @param string $place select public or private storage
     *
     * @return bool
     */
    public function upload($parentModel, $file, $fileName = false, $parentId = 0, $place = 'public', $subdirectory = '')
    {

        $this->fileModel = new Files();
        $this->fileModel->owner_id = \Auth::id();
        $this->fileModel->parent_type = get_class($parentModel);
        $this->fileModel->parent_id = $parentId;
        $this->fileModel->driver = $this->driver->getDriverName();
        $meta = $this->file->getMetaData($file);
        $this->fileModel->meta_data = $meta;

        if ($fileName) {
            if ($fileName === true) {
                $this->fileModel->title = $file->getClientOriginalName();
            } else {
                $this->fileModel->title = $fileName;
            }
        } else {
            if (isset($parentModel->title)) {
                $this->fileModel->title = $parentModel->title;
            } else {
                if (isset($parentModel->name)) {
                    $this->fileModel->title = $parentModel->name;
                } else {
                    $this->fileModel->title = $file->getClientOriginalName();
                }
            }
        }

        $this->fileModel->file = time() . '_' . strtolower(str_slug($this->fileModel->title,
                    '-') . '.' . $file->getClientOriginalExtension());


        if (!$this->driver->save($file, $place . "/" . $this->pathFromModel($parentModel) . $subdirectory, $this->fileModel->file)) {
            $this->errors = $this->driver->getErrors();

            return false;
        }

        $this->fileModel->file = $this->pathFromModel($parentModel) . $subdirectory . '/' . $this->fileModel->file;

        try {
            $this->fileModel->save();
            \Log::info('Użytkownik o id ' . \Auth::id() . '. dodał plik ', ['file' => $this->fileModel]);

            return true;
        } catch (\Illuminate\Database\QueryException $e) {
            $this->errors = $e->getMessage();

            return false;
        }

    }

    /**
     * Get class name for directory name
     *
     * @param Model $model
     * @return string
     */
    public
    function pathFromModel($model)
    {
        return strtolower((new \ReflectionClass($model))->getShortName());
    }

    /**
     * Get fileModel id
     * @return int|mixed
     */
    public
    function getId()
    {
        return $this->fileModel->id;
    }

    /**
     * Get errors
     *
     * @return array
     */
    public
    function getErrors()
    {
        return $this->errors;
    }

    /**
     * Crop image to desired size
     * @param float[] $params
     * @param Image $parentModel
     * @param int|null $coverId
     * @return bool
     * @throws \Exception
     */
    public
    function crop($params, $parentModel, $coverId = null)
    {
        if (!method_exists($this->file, 'crop')) {
            throw new \Exception('No crop method');
        }

        if (is_numeric($coverId)) {
            $this->fileModel = Files::find($coverId);
        }

        $this->file->crop($this->fileModel, $params);
        $croppedImage = $this->file->getImage();

        $croppedFilename = $this->file->getParentFilename() .
            '-crop-' . $croppedImage->getWidth() .
            'x' .
            $croppedImage->getHeight() .
            '.' . $croppedImage->extension;

        $this->driver->save(new File($this->file->getTemporaryFile()), "public/" . $this->pathFromModel($parentModel),
            $croppedFilename);

        $parentFileModel = $this->fileModel;

        $this->fileModel = new Files();
        $this->fileModel->owner_id = \Auth::id();
        $this->fileModel->parent_type = get_class($parentModel);
        $this->fileModel->parent_id = $parentFileModel->id;
        $this->fileModel->driver = $this->driver->getDriverName();
        $this->fileModel->meta_data = $parentFileModel->meta_data;
        $this->fileModel->file = $this->pathFromModel($parentModel) . '/' . $croppedFilename;

        if (isset($parentModel->title)) {
            $this->fileModel->title = $parentModel->title;
        } else {
            if (isset($parentModel->name)) {
                $this->fileModel->title = $parentModel->name;
            } else {
                $this->fileModel->title = $parentFileModel->title;
            }
        }

        $this->file->destroyImg();

        try {
            $this->fileModel->save();
            \Log::info('Użytkownik o id ' . \Auth::id() . '. przyciął plik ', ['file' => $this->fileModel]);

            return true;
        } catch (\Illuminate\Database\QueryException $e) {
            $this->errors = $e->getMessage();

            return false;
        }

    }

    /**
     * Get filename
     *
     * @return array
     */
    public
    function getFileName()
    {
        return $this->fileModel->title;
    }

    /**
     * Get file extension
     *
     * @return array
     */
    public
    function getExtension()
    {
        return $this->file->getExtension();
    }

}