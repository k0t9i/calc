<?php

namespace tests\models;

use app\models\MinusToken;
use Codeception\Test\Unit;

class MinusTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new MinusToken();
    }
}