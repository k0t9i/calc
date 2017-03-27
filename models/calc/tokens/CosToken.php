<?php

namespace app\models\calc\tokens;

/**
 * Class CosToken
 *
 * @package app\models\calc\tokens
 */
class CosToken extends FuncToken
{
    const LEXEME = 'cos';

    /**
     * @inheritdoc
     */
    protected function doGetValue(array $args)
    {
        return cos($args[0]->getValue());
    }

    /**
     * @inheritdoc
     */
    public function getLexemeRegExp()
    {
        return '[cC][oO][sS]';
    }

    /**
     * @inheritdoc
     */
    public function getLexeme()
    {
        return self::LEXEME;
    }
}
