<?php

namespace tests\models;

use app\models\tokens\DivToken;
use Codeception\Test\Unit;

class DivTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new DivToken(1);
    }
}