<?php

namespace tests\models;

use app\models\calc\tokens\MulToken;
use app\models\calc\tokens\NumToken;
use Codeception\Test\Unit;

class MulTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new MulToken(1);
    }

    public function testRequireTwoArgs()
    {
        $token = new MulToken(0);
        $this->assertTrue($token->argsCount() == 2);
    }

    public function testGetValue()
    {
        $token = new MulToken(0);
        $args = [new NumToken(0, '5'), new NumToken(0, '3')];
        $this->assertTrue($token->getValue($args) == 15);
    }

    public function testGetLexeme()
    {
        $token = new MulToken(0);
        $this->assertTrue(preg_match($token->getLexemeFullRegExp(), $token->getLexeme()) == 1);
    }
}