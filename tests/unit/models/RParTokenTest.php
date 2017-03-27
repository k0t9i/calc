<?php

namespace tests\models;

use app\models\calc\tokens\RParToken;
use Codeception\Test\Unit;

class RParTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new RParToken(1);
    }

    public function testGetLexeme()
    {
        $token = new RParToken(0);
        $this->assertTrue(preg_match($token->getLexemeFullRegExp(), $token->getLexeme()) == 1);
    }
}