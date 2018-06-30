<?php

namespace Someline\Component\File\Http\Controllers\Console;

use Someline\Http\Controllers\BaseController;

class SomelineFileControllerBase extends BaseController
{

    public function getFileList()
    {
        return view('console.files.list');
    }

    public function getFileNew()
    {
        return view('console.files.new');
    }

    public function getFileDetail($file_id)
    {
        return view('console.files.detail', compact('file_id'));
    }

    public function getFileEdit($file_id)
    {
        return view('console.files.edit', compact('file_id'));
    }

}