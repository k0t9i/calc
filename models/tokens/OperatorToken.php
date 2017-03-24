<?php

namespace app\models\tokens;

abstract class OperatorToken extends Token
{
    const ASSOCIATIVE_LEFT = 'associativeLeft';
    const ASSOCIATIVE_RIGHT = 'associativeRight';

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

    public function isAssociativeRight()
    {
        return $this->getAssociativity() == self::ASSOCIATIVE_RIGHT;
    }

    public function isAssociativeLeft()
    {
        return $this->getAssociativity() == self::ASSOCIATIVE_LEFT;
    }

    abstract function getPriority();
    abstract function getAssociativity();
}