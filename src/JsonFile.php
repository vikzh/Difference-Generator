<?php

namespace Differ;

class JsonFile extends File
{
    public function fileParse()
    {
        // TODO: Implement fileParse() method.
        return json_decode(file_get_contents($this->file), true);
    }
}
