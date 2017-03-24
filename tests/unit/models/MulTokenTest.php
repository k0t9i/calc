<?php

namespace tests\models;

use app\models\tokens\MulToken;
use Codeception\Test\Unit;

class MulTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new MulToken(1);
    }
}