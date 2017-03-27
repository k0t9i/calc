<?php

namespace tests\models;

use app\models\calc\tokens\PiToken;
use Codeception\Test\Unit;

class PiTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new PiToken(1);
    }

    public function testGetLexeme()
    {
        $token = new PiToken(0);
        $this->assertTrue($token->getLexeme() == PiToken::LEXEME);
    }

    public function testGetValue()
    {
        $token = new PiToken(0);
        $this->assertTrue($token->getValue() == pi());
    }
}