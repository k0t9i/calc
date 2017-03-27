<?php

namespace app\models\calc;

use app\models\calc\interfaces\ICalculator;
use app\models\calc\interfaces\IConverter;
use app\models\calc\tokens\ConstToken;
use app\models\calc\tokens\NumToken;
use app\models\calc\tokens\OperatorToken;

/**
 * Class PostfixNotationCalculator
 *
 * @package app\models\calc
 */
class PostfixNotationCalculator implements ICalculator
{
    /**
     * Converter object
     *
     * @var IConverter
     */
    private $converter;

    /**
     * PostfixNotationCalculator constructor.
     *
     * @param IConverter $converter
     */
    public function __construct(IConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Calculate expression
     *
     * @param string $expression
     * @return float Result of expression
     * @throws CalculateSyntaxException If expression has syntax error
     */
    public function calculate($expression)
    {
        $tokens = $this->converter->convert($expression);

        $result = 0;
        $stack = new \SplStack();

        while (count($tokens) > 0) {
            $current = array_shift($tokens);
            if ($current instanceof ConstToken) {
                $stack->push($current);
            } elseif ($current instanceof OperatorToken) {
                $args = [];
                for ($i = 0; $i < $current->argsCount(); $i++) {
                    if ($stack->count() == 0) {
                        throw new CalculateSyntaxException(
                            'Syntax error at position ' .
                            $current->getPosition() .
                            ' near lexeme "' .
                            $current->getLexeme() . '"'
                        );
                    }
                    $args[] = $stack->pop();
                }
                $stack->push(new NumToken(0, $current->getValue(array_reverse($args))));
            }
        }

        if ($stack->count() > 1) {
            throw new CalculateSyntaxException('Syntax error');
        }
        if ($stack->count() > 0) {
            $result = $stack->pop()->getValue();
        }

        return $result;
    }
}
