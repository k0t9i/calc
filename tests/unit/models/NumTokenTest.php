<?php

namespace tests\models;

use app\models\NumToken;
use Codeception\Test\Unit;

class NumTokenTest extends Unit
{
    public function testRequiresValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        new NumToken();
    }

    public function testGetValue()
    {
        $t = new NumToken('123');
        $this->assertTrue($t->getValue() == '123');
    }

    public function testRequiresZeroArgs()
    {
        $this->expectException(\InvalidArgumentException::class);
        $t = new NumToken('123');
        $t->getValue(1, 2, 3);
    }
}