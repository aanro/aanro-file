<?php

namespace Someline\Component\File\Models\Traits;


use Someline\Models\File\SomelineFile;

trait SomelineHasOneFileTrait
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function someline_file()
    {
        return $this->hasOne(SomelineFile::class, 'someline_file_id', 'someline_file_id');
    }

    /**
     * @return SomelineFile|null
     */
    public function getSomelineFile()
    {
        return $this->someline_file;
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|null|string
     */
    public function getSomelineFileUrl()
    {
        $somelineFile = $this->getSomelineFile();
        if (!$somelineFile) {
            return $this->getSomelineFileDefaultUrl();
        } else {
            return $somelineFile->getFileUrl();
        }
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|null|string
     */
    public function getSomelineFileUrlAttribute()
    {
        return $this->getSomelineFileUrl();
    }

    /**
     * @return int|null
     */
    public function getSomelineFileId()
    {
        return $this->someline_file_id;
    }

    /**
     * @return null|string
     */
    public function getSomelineFileName()
    {
        $someline_file = $this->getSomelineFile();
        if ($someline_file) {
            return $someline_file->getFileName();
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getSomelineFileNameAttribute()
    {
        return $this->getSomelineFileName();
    }

    /**
     * @return null
     */
    protected function getSomelineFileDefaultUrl()
    {
        return null;
    }

}