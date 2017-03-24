<?php

namespace app\models\tokens;

/**
 * Plus token
 *
 * @package app\models\tokens
 */
class PlusToken extends OperatorToken
{
    const LEXEME = '+';

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        return $args[0]->getValue() + $args[1]->getValue();
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
    public function getLexemeRegExp()
    {
        return '\\' . $this->getLexeme();
    }

    public function getPrecedence()
    {
        return 1;
    }

    public function getAssociativity()
    {
        return self::ASSOCIATIVE_LEFT;
    }

    public function getLexeme()
    {
        return self::LEXEME;
    }
}