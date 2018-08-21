<?php

namespace Differ\tests;

use PHPUnit\Framework\TestCase;

class CalcDiffTest extends TestCase
{
    public function testCalcDiff()
    {
        $jsonDiff = <<<DOC
  host: domain.io
+ timeout: 20
- timeout: 50
- proxy: 123.234.53.22
+ verbose: true
DOC;
        $this->assertEquals($jsonDiff, implode(
            PHP_EOL,
            \Differ\calcDiff(
                'tests/data/before.json',
                'tests/data/after.json',
                'json'
            )
        ));
    }
}
