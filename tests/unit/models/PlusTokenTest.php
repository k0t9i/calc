<?php

namespace tests\models;

use app\models\PlusToken;
use Codeception\Test\Unit;

class PlusTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new PlusToken();
    }
}