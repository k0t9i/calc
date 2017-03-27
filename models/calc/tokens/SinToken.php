<?php

namespace app\models\calc\tokens;

class SinToken extends FuncToken
{
    const LEXEME = 'sin';

    /**
     * @inheritdoc
     */
    public function getLexemeRegExp()
    {
        return '[sS][iI][nN]';
    }

    /**
     * @inheritdoc
     */
    public function getLexeme()
    {
        return self::LEXEME;
    }
}
