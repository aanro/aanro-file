<?php

namespace Someline\Component\File\Repositories\Eloquent;

use Someline\Repositories\Eloquent\BaseRepository;
use Someline\Repositories\Criteria\RequestCriteria;
use Someline\Repositories\Interfaces\SomelineFileRepository;
use Someline\Models\File\SomelineFile;
use Someline\Validators\SomelineFileValidator;
use Someline\Component\File\Presenters\SomelineFilePresenter;

/**
 * Class SomelineFileRepositoryEloquentBase
 * @package namespace Someline\Component\File\Repositories\Eloquent;
 */
class SomelineFileRepositoryEloquentBase extends BaseRepository implements SomelineFileRepository
{

    protected $fieldSearchable = [
        'title' => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SomelineFile::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return SomelineFileValidator::class;
    }


    /**
     * Specify Presenter class name
     *
     * @return mixed
     */
    public function presenter()
    {

        return SomelineFilePresenter::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
