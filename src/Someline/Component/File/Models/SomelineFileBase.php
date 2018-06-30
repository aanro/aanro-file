<?php

namespace Someline\Component\File\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Someline\Component\File\SomelineFileService;
use Someline\Models\BaseModel;
use Someline\Models\Foundation\User;
use Someline\Models\Traits\RelationUserTrait;

class SomelineFileBase extends BaseModel implements Transformable
{
    use TransformableTrait;
    use RelationUserTrait;
    use SoftDeletes;

    const MORPH_NAME = 'SomelineFile';

    protected $table = 'someline_files';

    protected $primaryKey = 'someline_file_id';

    protected $fillable = [
        'user_id',
        'file_name',
        'mime_type',
        'guess_extension',
        'original_name',
        'original_extension',
        'original_mime_type',
        'file_size',
        'file_sha1',
    ];

    // Fields to be converted to Carbon object automatically
    protected $dates = [
    ];

    protected $appends = [
        'file_url'
    ];

    /**
     * @return mixed
     */
    public function getSomelineFileId()
    {
        return $this->someline_file_id;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * @return mixed
     */
    public function getOriginalName()
    {
        return $this->original_name;
    }

    /**
     * @return mixed
     */
    public function getOriginalExtension()
    {
        return $this->original_extension;
    }

    /**
     * @return mixed
     */
    public function getFileSize()
    {
        return $this->file_size;
    }

    /**
     * @return string
     */
    public function getFileUrlAttribute()
    {
        return $this->getFileUrl();
    }

    /**
     * @return string
     */
    public function getFileUrl()
    {
        return SomelineFileService::getFullFileUrl($this->getFileName());
    }

    /**
     * @return string
     */
    public function getFileStoragePath()
    {
        return SomelineFileService::getFullFileStoragePath($this->getFileName());
    }

    /**
     * @return array
     */
    public function toSimpleArray()
    {
        $somelineFile = $this;
        return [
            'someline_file_id' => $somelineFile->getSomelineFileId(),
            'someline_file_url' => $somelineFile->getFileUrl(),
            'original_name' => $somelineFile->getOriginalName(),
        ];
    }

}
