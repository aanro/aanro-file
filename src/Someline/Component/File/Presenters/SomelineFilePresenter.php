<?php

namespace Someline\Component\File\Presenters;

use Someline\Transformers\SomelineFileTransformer;
use Someline\Presenters\BasePresenter;

/**
 * Class SomelineFilePresenter
 *
 * @package namespace Someline\Component\File\Presenters;
 */
class SomelineFilePresenter extends BasePresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new SomelineFileTransformer();
    }
}
