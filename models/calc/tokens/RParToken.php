<?php

namespace app\models\calc\tokens;

/**
 * Right parentheses token
 *
 * @package app\models\calc\tokens
 */
class RParToken extends Token
{
    const LEXEME = ')';

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function argsCount()
    {
        return 0;
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

    /**
     * @inheritdoc
     */
    public function getLexeme()
    {
        return self::LEXEME;
    }
}
