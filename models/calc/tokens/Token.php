<?php

namespace app\models\calc\tokens;

/**
 * Parent class for all tokens
 *
 * @package app\models\calc\tokens
 */
abstract class Token
{
    /**
     * Token value
     *
     * @var string
     */
    protected $value;

    /**
     * Token position in expression
     *
     * @var integer
     */
    private $position;

    /**
     * @var Token[] Existing token types
     */
    private static $tokenTypes = [];

    /**
     * Token constructor.
     *
     * @param integer $position Token position in expression
     * @param null|String $value Token value
     */
    final public function __construct($position, $value = null)
    {
        if ($this->requireValue() !== false && is_null($value)) {
            throw new \InvalidArgumentException('Value required');
        }
        if ($this->requireValue()) {
            $this->value = $value;
        }
        $this->position = (int) $position;
    }

    /**
     * Call child method doGetValue
     *
     * @param ConstToken[] $args Token arguments for getting value
     * @return string
     */
    public function getValue(array $args = [])
    {
        if ($this->argsCount() != count($args)) {
            throw new \InvalidArgumentException('Invalid args count');
        }

        return $this->doGetValue($args);
    }

    /**
     * Token position in expression
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Create new token
     *
     * @param integer $position Token position in expression
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
     * Lexeme for concrete Token
     *
     * @return string
     */
    public function getLexeme()
    {
        return $this->value;
    }

    /**
     * Lexeme full regular expression for concrete Token: '/^' . RegExp . '/'
     *
     * @return string
     */
    public function getLexemeFullRegExp()
    {
        return '/^' . $this->getLexemeRegExp() . '/';
    }

    /**
     * Return true if Token matched first lexeme in expression, otherwise return false
     *
     * @param $expression
     * @param array|null $matches
     * @return bool
     */
    public function isFirstLexemeMatched($expression, array &$matches = null)
    {
        return preg_match($this->getLexemeFullRegExp(), $expression, $matches) && $matches[0];
    }

    /**
     * Remove token lexeme from beginning of the expression
     *
     * @param $expression
     * @return string
     */
    public function removeLexemeFromBeginning($expression)
    {
        return preg_replace($this->getLexemeFullRegExp(), '', $expression, 1);
    }

    /**
     * Register existing token types
     *
     * @return Token[]
     */
    private static function registerTokenTypes()
    {
        if (!self::$tokenTypes) {
            self::registerTokenType(new NumToken(0, 0));
            self::registerTokenType(new PiToken(0));
            self::registerTokenType(new PlusToken(0));
            self::registerTokenType(new MinusToken(0));
            self::registerTokenType(new MulToken(0));
            self::registerTokenType(new DivToken(0));
            self::registerTokenType(new PowToken(0));
            self::registerTokenType(new LParToken(0));
            self::registerTokenType(new RParToken(0));
            self::registerTokenType(new SinToken(0));
            self::registerTokenType(new CosToken(0));
        }

        return self::$tokenTypes;
    }

    /**
     * Register token type
     *
     * @param Token $token
     */
    private static function registerTokenType(Token $token)
    {
        self::$tokenTypes[$token->getLexemeFullRegExp()] = $token;
    }

    /**
     * Return concrete token value
     *
     * @param ConstToken[] $args
     * @return mixed
     */
    abstract protected function doGetValue(array $args);

    /**
     * Token::getValue arguments count for concrete Token
     *
     * @return integer
     */
    abstract public function argsCount();

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
    abstract public function getLexemeRegExp();
}
