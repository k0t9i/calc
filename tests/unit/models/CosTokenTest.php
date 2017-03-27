<?php

namespace tests\models;

use app\models\calc\tokens\CosToken;
use app\models\calc\tokens\NumToken;
use Codeception\Test\Unit;

class CosTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new CosToken(1);
    }

    public function testRequireOneArgs()
    {
        $token = new CosToken(0);
        $this->assertTrue($token->argsCount() == 1);
    }

    public function testGetValue()
    {
        $token = new CosToken(0);
        $args = [new NumToken(0, pi() / 2)];
        $this->assertEquals(0, $token->getValue($args), 0.000001);
    }

    public function testGetLexeme()
    {
        $token = new CosToken(0);
        $this->assertTrue(preg_match($token->getLexemeFullRegExp(), $token->getLexeme()) == 1);
    }
}