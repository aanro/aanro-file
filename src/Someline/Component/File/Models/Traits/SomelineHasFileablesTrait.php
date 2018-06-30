<?php

namespace Someline\Component\File\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Someline\Models\File\SomelineFile;

trait SomelineHasFileablesTrait
{

    /**
     * @return mixed|MorphToMany
     */
    public function someline_files()
    {
        return $this->morphToMany(SomelineFile::class, 'fileable', 'someline_fileables', null, 'someline_file_id')
            ->withPivot('original_name', 'type', 'data');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function type_someline_files($type)
    {
        return $this->someline_files()->wherePivot('type', '=', $type);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSomelineFiles()
    {
        return $this->someline_files;
    }

    /**
     * @param $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTypeFiles($type)
    {
        return $this->type_someline_files($type)->get();
    }

}