<?php

namespace tests\models;

use app\models\PlusToken;
use Codeception\Test\Unit;

class PlusTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new PlusToken();
    }

    public function testRequiresTwoArgs()
    {
        $this->expectException(\InvalidArgumentException::class);
        $t = new PlusToken();
        $t->getValue(1);
        $t->getValue(1, 2);
    }

    public function testGetValue()
    {
        $t = new PlusToken();
        $this->assertTrue($t->getValue(4, 5) == 9);
    }
}