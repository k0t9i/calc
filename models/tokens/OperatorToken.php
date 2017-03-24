<?php

namespace app\models\tokens;

abstract class OperatorToken extends Token
{
    const ASSOCIATIVE_LEFT = 'associativeLeft';
    const ASSOCIATIVE_RIGHT = 'associativeRight';

    public function comparePrecedence(OperatorToken $other)
    {
        if ($this->getPrecedence() > $other->getPrecedence()) {
            return 1;
        }
        if ($this->getPrecedence() < $other->getPrecedence()) {
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

    abstract function getPrecedence();
    abstract function getAssociativity();
}