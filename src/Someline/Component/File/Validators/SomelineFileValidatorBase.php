<?php

namespace Someline\Component\File\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class SomelineFileValidatorBase extends LaravelValidator
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'file' => 'required|file',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'file' => 'required|file',
        ],
    ];
}
