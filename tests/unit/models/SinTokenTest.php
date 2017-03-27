<?php

namespace tests\models;

use app\models\calc\tokens\NumToken;
use app\models\calc\tokens\SinToken;
use Codeception\Test\Unit;

class SinTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new SinToken(1);
    }

    public function testRequireOneArgs()
    {
        $token = new SinToken(0);
        $this->assertTrue($token->argsCount() == 1);
    }

    public function testGetValue()
    {
        $token = new SinToken(0);
        $args = [new NumToken(0, pi() / 2)];
        $this->assertTrue($token->getValue($args) == 1);
    }

    public function testGetLexeme()
    {
        $token = new SinToken(0);
        $this->assertTrue(preg_match($token->getLexemeFullRegExp(), $token->getLexeme()) == 1);
    }
}