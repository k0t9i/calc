<?php

namespace tests\models;

use app\models\calc\tokens\LParToken;
use Codeception\Test\Unit;

class LParTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new LParToken(1);
    }

    public function testGetLexeme()
    {
        $token = new LParToken(0);
        $this->assertTrue(preg_match($token->getLexemeFullRegExp(), $token->getLexeme()) == 1);
    }
}