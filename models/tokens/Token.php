<?php

namespace app\models\tokens;
use app\models\Lexeme;

/**
 * Parent class for all tokens
 *
 * @package app\models
 */
abstract class Token
{
    /**
     * Token value
     *
     * @var string
     */
    protected $_value;

    /**
     * Token position
     *
     * @var integer
     */
    protected $_position;

    /**
     * @var Token[] Existing token types
     */
    private static $_tokenTypes = [];

    /**
     * Token constructor.
     *
     * @param integer $position Token position
     * @param null|String $value Token value
     */
    final public function __construct($position, $value = null)
    {
        if ($this->requireValue() !== false && is_null($value)) {
            throw new \InvalidArgumentException();
        }
        if ($this->requireValue()) {
            $this->_value = $value;
        }
        $this->_position = (int) $position;
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
     * @param integer $position Token position
     * @param null|String $value Token value
     * @return static
     */
    public function create($position, $value = null)
    {
        return new static($position, $value);
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
            self::registerTokenType(new NumToken(0, 0));
            self::registerTokenType(new PlusToken(0));
            self::registerTokenType(new MinusToken(0));
            self::registerTokenType(new MulToken(0));
            self::registerTokenType(new DivToken(0));
            self::registerTokenType(new LParToken(0));
            self::registerTokenType(new RParToken(0));
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