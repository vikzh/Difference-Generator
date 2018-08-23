<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\generateDiff;

class CalcDiffTest extends TestCase
{
    public function testCalcDiff()
    {
        $correctDiff = <<<DOC
  host: domain.io
+ timeout: 20
- timeout: 50
- proxy: 123.234.53.22
+ verbose: true
DOC;
        $this->assertEquals($correctDiff, implode(
            PHP_EOL,
            generateDiff(
                'tests/data/before.json',
                'tests/data/after.json',
                'json'
            )
        ));


        $this->assertEquals($correctDiff, implode(
            PHP_EOL,
            generateDiff(
                'tests/data/before.yml',
                'tests/data/after.yml',
                'yml'
            )
        ));
    }
}
