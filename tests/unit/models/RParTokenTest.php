<?php

namespace tests\models;

use app\models\tokens\RParToken;
use Codeception\Test\Unit;

class RParTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new RParToken();
    }
}