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
}