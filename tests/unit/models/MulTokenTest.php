<?php

namespace tests\models;

use app\models\MulToken;
use Codeception\Test\Unit;

class MulTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new MulToken();
    }
}