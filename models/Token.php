<?php

namespace app\models;

abstract class Token
{
    protected $_value;
    private static $_tokenTypes = [];

    final public function __construct($value = null)
    {
        if ($this->requireValue() !== false && is_null($value)) {
            throw new \InvalidArgumentException();
        }
        if ($this->requireValue()) {
            $this->_value = $value;
        }
    }

    public function getValue()
    {
        $args = func_get_args();
        if ($this->argsCount() != count($args)) {
            throw new \InvalidArgumentException();
        }

        return $this->doGetValue($args);
    }

    public function create($value = null)
    {
        return new static($value);
    }

    public static function getTokenTypes()
    {
        return self::registerTokenTypes();
    }

    private static function registerTokenTypes()
    {
        if (!self::$_tokenTypes) {
            self::registerTokenType(new NumToken(0));
            self::registerTokenType(new PlusToken());
            self::registerTokenType(new MinusToken());
            self::registerTokenType(new MulToken());
            self::registerTokenType(new DivToken());
            self::registerTokenType(new LParToken());
            self::registerTokenType(new RParToken());
        }

        return self::$_tokenTypes;
    }

    private static function registerTokenType(Token $token)
    {
        self::$_tokenTypes[$token->getLexemeRegExp()] = $token;
    }

    abstract protected function doGetValue(array $args);
    abstract protected function argsCount();
    abstract protected function requireValue();
    abstract protected function getLexemeRegExp();
}