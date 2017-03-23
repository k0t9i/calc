<?php

namespace app\models\tokens;

/**
 * Parent class for all tokens
 *
 * @package app\models
 */
abstract class Token
{
    protected $_value;
    private static $_tokenTypes = [];

    /**
     * Token constructor.
     *
     * @param mixed $value Token value
     */
    final public function __construct($value = null)
    {
        if ($this->requireValue() !== false && is_null($value)) {
            throw new \InvalidArgumentException();
        }
        if ($this->requireValue()) {
            $this->_value = $value;
        }
    }

    /**
     * Call child method doGetValue
     *
     * @return mixed
     */
    public function getValue()
    {
        $args = func_get_args();
        if ($this->argsCount() != count($args)) {
            throw new \InvalidArgumentException();
        }

        return $this->doGetValue($args);
    }

    /**
     * Create new token
     *
     * @param mixed $value Token value
     * @return static
     */
    public function create($value = null)
    {
        return new static($value);
    }

    /**
     * Return all registered token types
     *
     * @return Token[]
     */
    public static function getTokenTypes()
    {
        return self::registerTokenTypes();
    }

    /**
     * Register existing token types
     *
     * @return Token[]
     */
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

    /**
     * Register token type
     *
     * @param Token $token
     */
    private static function registerTokenType(Token $token)
    {
        self::$_tokenTypes[$token->getLexemeRegExp()] = $token;
    }

    /**
     * Return concrete token value
     *
     * @param array $args
     * @return mixed
     */
    abstract protected function doGetValue(array $args);

    /**
     * Token::getValue arguments count for concrete Token
     *
     * @return integer
     */
    abstract protected function argsCount();

    /**
     * Is value required
     *
     * @return boolean
     */
    abstract protected function requireValue();

    /**
     * Lexeme regular expression for concrete Token
     *
     * @return string
     */
    abstract protected function getLexemeRegExp();
}