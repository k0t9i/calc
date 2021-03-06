<?php

namespace app\models\calc;

use app\models\calc\interfaces\IConverter;
use app\models\calc\interfaces\ITokenizer;
use app\models\calc\tokens\ConstToken;
use app\models\calc\tokens\FuncToken;
use app\models\calc\tokens\OperatorToken;
use app\models\calc\tokens\LParToken;
use app\models\calc\tokens\NumToken;
use app\models\calc\tokens\RParToken;
use app\models\calc\tokens\Token;

/**
 * Class PostfixNotationConverter
 *
 * @package app\models\calc
 */
class PostfixNotationConverter implements IConverter
{
    /**
     * Tokenizer object
     *
     * @var ITokenizer
     */
    private $tokenizer;

    /**
     * PostfixNotationConverter constructor.
     *
     * @param ITokenizer $tokenizer
     */
    public function __construct(ITokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * Convert expression in infix notation to array of tokens in postfix notation with Shunting-yard algorithm
     *
     * @param string $expression
     * @return Token[]
     * @throws \Exception If not found left parentheses in stack
     */
    public function convert($expression)
    {
        $tokens = $this->tokenizer->tokenize($expression);

        $stack = new \SplStack();
        $output = [];

        while (count($tokens) > 0) {
            $current = array_shift($tokens);
            $this->processToken($stack, $current, $output);
        }

        while ($stack->count() > 0) {
            $current = $stack->pop();
            if (!($current instanceof OperatorToken)) {
                throw new ConvertException('Mismatched parentheses at position ' . $current->getPosition());
            }
            $output[] = $current;
        }

        return $output;
    }

    /**
     * Process current token
     * Change $output
     *
     * @param \SplStack $stack Stack of operators
     * @param Token $token Current token
     * @param Token[] $output Output array of tokens
     * @throws ConvertException If parentheses mismatched
     */
    private function processToken(\SplStack $stack, $token, &$output)
    {
        if ($token instanceof ConstToken) {
            $output[] = $token;
        } elseif ($token instanceof FuncToken) {
            $stack->push($token);
        } elseif ($token instanceof LParToken) {
            $stack->push($token);
        } elseif ($token instanceof RParToken) {
            if ($stack->count() == 0) {
                throw new ConvertException('Mismatched right parentheses at position ' . $token->getPosition());
            }
            $this->popTokensWhileNotFoundLeftParentheses($stack, $output);
        } elseif ($token instanceof OperatorToken) {
            $this->popTokensWhileOperatorGoBeforeCurrent($stack, $token, $output);
        }
    }

    /**
     * Check if operator in stack must go before current operator in postfix notation
     * If current operator is left associative and its precedence less than or equal to precedence of operator in stack
     * or operator is right associative and its precedence less than precedence of operator in stack
     * then return true, false otherwise
     *
     * @param OperatorToken $inStack Operator token in stack
     * @param OperatorToken $current Current operator token
     * @return bool
     */
    private function isOperatorBeforeOther(OperatorToken $inStack, OperatorToken $current)
    {
        return $current->isAssociativeLeft()  && $current->comparePrecedence($inStack) <= 0 ||
               $current->isAssociativeRight() && $current->comparePrecedence($inStack) <  0;
    }

    /**
     * Pop tokens into output array from stack while token is not left parentheses and stack is not empty
     * Change $output
     *
     * @param \SplStack $stack Stack of operators
     * @param Token[] $output Output array of tokens
     * @throws \Exception If not found left parentheses in stack (@see Shunting-yard algorithm)
     */
    private function popTokensWhileNotFoundLeftParentheses(\SplStack $stack, &$output)
    {
        $result = $stack->pop();
        while (!($result instanceof LParToken)) {
            $output[] = $result;
            if ($stack->count() == 0) {
                break;
            }
            $result = $stack->pop();
        }

        if (!($result instanceof LParToken)) {
            throw new ConvertException('Mismatched left parentheses');
        }
    }

    /**
     * Pop tokens into output array from stack while operator must go before current and stack is not empty
     * Change $output
     *
     * @param \SplStack $stack Stack of operators
     * @param Token[] $output Output array of tokens
     * @param OperatorToken $current Current operator
     */
    private function popTokensWhileOperatorGoBeforeCurrent(\SplStack $stack, $current, &$output)
    {
        if ($stack->count() > 0) {
            $top = $stack->top();
            while ($top instanceof OperatorToken && $this->isOperatorBeforeOther($top, $current)) {
                $output[] = $stack->pop();
                if ($stack->count() == 0) {
                    break;
                }
                $top = $stack->top();
            }
        }
        $stack->push($current);
    }
}
