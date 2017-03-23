<?php

namespace tests\models;

use app\models\LParToken;
use Codeception\Test\Unit;

class LParTokenTest extends Unit
{
    public function testNotRequiresValue()
    {
        new LParToken();
    }
}