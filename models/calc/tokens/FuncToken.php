<?php

namespace app\models\calc\tokens;

abstract class FuncToken extends OperatorToken
{
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
    public function argsCount()
    {
        return 1;
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
        return self::PRECEDENCE_HIGH;
    }

    /**
     * @inheritdoc
     */
    public function getAssociativity()
    {
        return self::ASSOCIATIVE_RIGHT;
    }
}
