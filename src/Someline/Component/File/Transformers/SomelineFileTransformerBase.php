<?php

namespace Someline\Component\File\Transformers;

use Someline\Models\File\SomelineFile;
use Someline\Transformers\BaseTransformer;

/**
 * Class SomelineFileTransformer
 * @package namespace Someline\Component\File\Transformers;
 */
class SomelineFileTransformerBase extends BaseTransformer
{

    /**
     * Transform the SomelineFile entity
     * @param SomelineFile $model
     *
     * @return array
     */
    public function transform(SomelineFile $model)
    {
        return [
            'someline_file_id' => (int)$model->someline_file_id,
            'user_id' => $model->getUserId(),

            /* place your other model properties here */
            'file_name' => $model->file_name,
            'mime_type' => $model->mime_type,
            'original_name' => $model->original_name,
            'original_extension' => $model->original_extension,
            'original_mime_type' => $model->original_mime_type,
            'file_size' => $model->file_size,
            'file_sha1' => $model->file_sha1,
            'file_url' => $model->getFileUrl(),

            'created_at' => (string)$model->created_at,
            'updated_at' => (string)$model->updated_at
        ];
    }
}
