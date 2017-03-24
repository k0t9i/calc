<?php

namespace app\models\tokens;

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

    public function getPrecedence()
    {
        return 3;
    }

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

    public function getLexeme()
    {
        return self::LEXEME;
    }

}