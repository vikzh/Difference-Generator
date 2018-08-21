<?php

namespace Differ;

use Symfony\Component\Yaml\Yaml;

class YamlFile extends File
{
    public function fileParse()
    {
        return Yaml::parse(file_get_contents($this->file), Yaml::PARSE_OBJECT);
    }
}
