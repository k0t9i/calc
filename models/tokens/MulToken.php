<?php

namespace app\models\tokens;

/**
 * Multiply token
 *
 * @package app\models\tokens
 */
class MulToken extends OperatorToken
{
    const LEXEME = '*';

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        return $args[0]->getValue() * $args[1]->getValue();
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

    public function getPrecedence()
    {
        return 2;
    }

    public function getAssociativity()
    {
        return self::ASSOCIATIVE_LEFT;
    }

    /**
     * @inheritdoc
     */
    public function getLexemeRegExp()
    {
        return '\\' . $this->getLexeme();
    }

    public function getLexeme()
    {
        return self::LEXEME;
    }
}
