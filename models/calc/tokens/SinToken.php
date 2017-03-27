<?php

namespace app\models\calc\tokens;

/**
 * Class SinToken
 *
 * @package app\models\calc\tokens
 */
class SinToken extends FuncToken
{
    const LEXEME = 'sin';

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        return sin($args[0]->getValue());
    }

    /**
     * @inheritdoc
     */
    public function getLexeme()
    {
        return self::LEXEME;
    }
}
