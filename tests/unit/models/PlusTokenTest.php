<?php

namespace tests\models;

use app\models\tokens\NumToken;
use app\models\tokens\PlusToken;
use Codeception\Test\Unit;

class PlusTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new PlusToken(1);
    }

    public function testRequireTwoArgs()
    {
        $token = new PlusToken(0);
        $this->assertTrue($token->argsCount() == 2);
    }

    public function testGetValue()
    {
        $token = new PlusToken(0);
        $args = [new NumToken(0, '5'), new NumToken(0, '3')];
        $this->assertTrue($token->getValue($args) == 8);
    }
}