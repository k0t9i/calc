<?php

namespace app\models\calc\tokens;

/**
 * Power token
 *
 * @package app\models\calc\tokens
 */
class PowToken extends OperatorToken
{
    const LEXEME = '^';

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        return pow($args[0]->getValue(), $args[1]->getValue());
    }

    /**
     * @inheritdoc
     */
    public function argsCount()
    {
        return 2;
    }

    /**
     * @inheritdoc
     */
    protected function requireValue()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getPrecedence()
    {
        return self::PRECEDENCE_AVERAGE;
    }

    /**
     * @inheritdoc
     */
    public function getAssociativity()
    {
        return self::ASSOCIATIVE_RIGHT;
    }

    /**
     * @inheritdoc
     */
    public function getLexemeRegExp()
    {
        return '\\' . $this->getLexeme();
    }

    /**
     * @inheritdoc
     */
    public function getLexeme()
    {
        return self::LEXEME;
    }
}
