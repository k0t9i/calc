<?php

namespace app\models\tokens;

abstract class OperatorToken extends Token
{
    public function comparePriority(OperatorToken $other)
    {
        if ($this->getPriority() > $other->getPriority()) {
            return 1;
        }
        if ($this->getPriority() < $other->getPriority()) {
            return -1;
        }
        return 0;
    }

    abstract function getPriority();
}