<?php

namespace tests\models;

use app\models\calc\tokens\NumToken;
use Codeception\Test\Unit;

class NumTokenTest extends Unit
{
    public function testRequiresValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        new NumToken(1);
    }

    public function testGetLexeme()
    {
        $token = new NumToken(0, '1234');
        $this->assertTrue($token->getLexeme() == 1234);
    }
}