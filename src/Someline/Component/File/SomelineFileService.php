<?php
/**
 * Created by PhpStorm.
 * User: Libern
 * Date: 7/7/17
 * Time: 2:50 PM
 */

namespace Someline\Component\File;


use File;
use Illuminate\Http\UploadedFile;
use Someline\Component\File\Exception\SomelineFileException;
use Someline\Component\File\Exception\StoreFileException;
use Someline\Models\File\SomelineFile;
use Storage;
use YueCode\Cos\QCloudCos;

class SomelineFileService
{

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getConfig($key, $default = null)
    {
        return config("someline-file.$key", $default);
    }

    /**
     * @return \Illuminate\Filesystem\FilesystemAdapter
     */
    public function disk()
    {
        return $this->localDisk();
    }

    /**
     * @return \Illuminate\Filesystem\FilesystemAdapter
     */
    public function localDisk()
    {
        return Storage::disk('local');
    }

    /**
     * @return string
     */
    public function storagePath()
    {
        return $this->localPublicFileStoragePath();
    }

    /**
     * @return string
     */
    public function defaultFileStoragePath()
    {
        return 'files/';
    }

    /**
     * @return string
     */
    public function localPublicPath()
    {
        return 'public/';
    }

    /**
     * @return string
     */
    public function localPublicFileStoragePath()
    {
        return rtrim($this->localPublicPath(), '/') . '/' . $this->defaultFileStoragePath();
    }

    /**
     * @param UploadedFile $file
     * @param array $additional_extensions_allowed
     * @param bool $useClientFileExtension
     * @return SomelineFile
     */
    public function handleUploadedFile(UploadedFile $file, array $additional_extensions_allowed = [], $useClientFileExtension = true)
    {
        // check valid
        if (!$file->isValid()) {
            throw new StoreFileException($file->getErrorMessage());
        }

        $file_path = $file->getPathname();
        $file_sha1 = sha1_file($file_path);
        $file_extension = $useClientFileExtension ? $file->getClientOriginalExtension() : $file->guessExtension();
        $file_name = $file_sha1 . '.' . $file_extension;

        // validate file extensions
        $default_allowed_extensions = $this->getConfig('allow_file_extensions', []);
        $allowed_extensions = array_merge($default_allowed_extensions, $additional_extensions_allowed);
        if (!in_arrayi($file_extension, $allowed_extensions)) {
            throw new StoreFileException('The file extension [' . $file_extension . '] is not allowed for uploading.');
        }

        // save in storage
        $file_path_in_storage = $this->saveInStorage($file, $file_name);
        // save in cos
        $result = QCloudCos::upload('audioplay',$this->getFullFileStoragePath($file_name),$file_name);

        // save in database
        $somelineFile = $this->saveInDatabase($file, $file_path, $file_name, $file_sha1);

        return $somelineFile;
    }

    /**
     * @param UploadedFile $file
     * @param $file_path
     * @param $file_name
     * @param $file_sha1
     * @return SomelineFile
     */
    protected function saveInDatabase(UploadedFile $file, $file_path, $file_name, $file_sha1)
    {
        if (empty($file) || empty($file_name) || empty($file_sha1)) {
            throw new SomelineFileException('One of required parameters is empty.');
        }

        /** @var SomelineFile $somelineFile */
        $somelineFile = SomelineFile::where(['file_sha1' => $file_sha1])->first();

        if ($somelineFile) {
            return $somelineFile;
        }

        $mimeType = $file->getMimeType();
        $guessExtension = $file->guessExtension();
        $clientOriginalName = $file->getClientOriginalName();
        $clientOriginalExtension = $file->getClientOriginalExtension();
        $clientMimeType = $file->getClientMimeType();
        $fileSize = filesize($file_path);

        $modelData = [
            'file_name' => $file_name,
            'mime_type' => $mimeType,
            'guess_extension' => $guessExtension,
            'original_name' => $clientOriginalName,
            'original_extension' => $clientOriginalExtension,
            'original_mime_type' => $clientMimeType,
            'file_size' => $fileSize,
            'file_sha1' => $file_sha1,
        ];

        $somelineFile = SomelineFile::create($modelData);

        return $somelineFile;
    }

    /**
     * @param $file
     * @param $name
     * @return string
     */
    protected function saveInStorage($file, $name)
    {
        if (empty($file) || empty($name)) {
            throw new SomelineFileException('One of required parameters is empty.');
        }

        $disk = $this->disk();
        $path = $this->storagePath();
        $path_with_name = rtrim($path, '/') . '/' . $name;

        // save file, exists will be overwritten
        $disk->putFileAs($path, $file, $name);

        // check if exists
        $result = $disk->exists($path_with_name);
        if (!$result) {
            throw new SomelineFileException('Failed to store files.');
        }

        return $path_with_name;
    }

    /**
     * @param $file_name
     * @return string
     */
    public static function getStoragePathForFile($file_name)
    {
        $localPublicFileStoragePath = rtrim((new SomelineFileService)->storagePath(), '/') . '/';
        return $localPublicFileStoragePath . $file_name;
    }

    /**
     * @param $file_name
     * @return string
     */
    public static function getFullFileUrl($file_name)
    {
        $localPublicFileStoragePath = rtrim((new SomelineFileService)->defaultFileStoragePath(), '/') . '/';
        return asset('storage/' . $localPublicFileStoragePath . $file_name);
    }

    /**
     * @param $file_name
     * @return string
     */
    public static function getFullFileStoragePath($file_name)
    {
        return storage_path('app/' . self::getStoragePathForFile($file_name));
    }

}