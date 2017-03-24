<?php

namespace tests\models;

use app\models\tokens\DivToken;
use app\models\tokens\NumToken;
use Codeception\Test\Unit;

class DivTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new DivToken(1);
    }

    public function testRequireTwoArgs()
    {
        $token = new DivToken(0);
        $this->assertTrue($token->argsCount() == 2);
    }

    public function testGetValue()
    {
        $token = new DivToken(0);
        $args = [new NumToken(0, '6'), new NumToken(0, '3')];
        $this->assertTrue($token->getValue($args) == 2);
    }

    public function testGetFloatValue()
    {
        $token = new DivToken(0);
        $args = [new NumToken(0, '6'), new NumToken(0, '4')];
        $this->assertTrue($token->getValue($args) == 1.5);
    }

    public function testGetLexeme()
    {
        $token = new DivToken(0);
        $this->assertTrue(preg_match($token->getLexemeFullRegExp(), $token->getLexeme()) == 1);
    }
}