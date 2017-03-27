<?php

namespace app\models\tokens;

/**
 * Divide token
 *
 * @package app\models\tokens
 */
class DivToken extends OperatorToken
{
    const LEXEME = '/';

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        return $args[0]->getValue() / $args[1]->getValue();
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
        return self::PRECEDENCE_LOW;
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public function getLexeme()
    {
        return self::LEXEME;
    }
}
