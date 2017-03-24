<?php

namespace app\models\tokens;

/**
 * Class OperatorToken
 *
 * @package app\models\tokens
 */
abstract class OperatorToken extends Token
{
    const ASSOCIATIVE_LEFT = 'associativeLeft';
    const ASSOCIATIVE_RIGHT = 'associativeRight';

    /**
     * Compare operators precedence
     *
     * @param OperatorToken $other Operator for compare
     * @return int 1 if greater than, 0 if equal to, -1 if lesser than
     */
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

    /**
     * Is operator associative right
     *
     * @return bool
     */
    public function isAssociativeRight()
    {
        return $this->getAssociativity() == self::ASSOCIATIVE_RIGHT;
    }

    /**
     * Is operator associative left
     *
     * @return bool
     */
    public function isAssociativeLeft()
    {
        return $this->getAssociativity() == self::ASSOCIATIVE_LEFT;
    }

    /**
     * Operator precendence
     *
     * @return integer
     */
    abstract public function getPrecedence();

    /**
     * Operator associativity: OperatorToken::ASSOCIATIVE_LEFT, OperatorToken::ASSOCIATIVE_RIGHT
     *
     * @return string
     */
    abstract public function getAssociativity();
}
