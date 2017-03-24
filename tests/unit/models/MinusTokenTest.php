<?php

namespace tests\models;

use app\models\tokens\MinusToken;
use app\models\tokens\NumToken;
use Codeception\Test\Unit;

class MinusTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new MinusToken(1);
    }

    public function testRequireTwoArgs()
    {
        $token = new MinusToken(0);
        $this->assertTrue($token->argsCount() == 2);
    }

    public function testGetValue()
    {
        $token = new MinusToken(0);
        $args = [new NumToken(0, '5'), new NumToken(0, '3')];
        $this->assertTrue($token->getValue($args) == 2);
    }

    public function testGetLexeme()
    {
        $token = new MinusToken(0);
        $this->assertTrue(preg_match($token->getLexemeFullRegExp(), $token->getLexeme()) == 1);
    }
}