<?php

namespace app\models\calc\tokens;

/**
 * Class PiToken
 *
 * @package app\models\calc\tokens
 */
class PiToken extends ConstToken
{
    const LEXEME = 'PI';

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        return pi();
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
        return '[P][I]';
    }

    /**
     * @inheritdoc
     */
    public function getLexeme()
    {
        return self::LEXEME;
    }
}
