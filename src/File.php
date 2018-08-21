<?php

namespace Differ;

abstract class File
{
    public $file;

    public function __construct($fileName)
    {
        $this->file = $fileName;
    }

    public function getFile()
    {
        return $this->file;
    }

    abstract public function fileParse();
}
