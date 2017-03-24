<?php

namespace app\models\tokens;

/**
 * Left parentheses token
 * 
 * @package app\models\tokens
 */
class LParToken extends Token
{
    const LEXEME = '(';

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        // TODO: Implement doGetValue() method.
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

    public function getLexeme()
    {
        return self::LEXEME;
    }
}
