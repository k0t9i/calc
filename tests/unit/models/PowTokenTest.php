<?php

namespace tests\models;

use app\models\tokens\NumToken;
use app\models\tokens\PowToken;
use Codeception\Test\Unit;

class PowTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new PowToken(1);
    }

    public function testRequireTwoArgs()
    {
        $token = new PowToken(0);
        $this->assertTrue($token->argsCount() == 2);
    }

    public function testGetValue()
    {
        $token = new PowToken(0);
        $args = [new NumToken(0, '2'), new NumToken(0, '3')];
        $this->assertTrue($token->getValue($args) == 8);
    }

    public function testGetLexeme()
    {
        $token = new PowToken(0);
        $this->assertTrue(preg_match($token->getLexemeFullRegExp(), $token->getLexeme()) == 1);
    }
}