<?php

namespace app\models;

use app\models\tokens\OperatorToken;
use app\models\tokens\LParToken;
use app\models\tokens\NumToken;
use app\models\tokens\RParToken;
use app\models\tokens\Token;

/**
 * Class PostfixNotation
 *
 * @package app\models
 */
class PostfixNotation
{
    /**
     * Convert array of tokens in infix notation to array of tokens in postfix notation with Shunting-yard algorithm
     *
     * @param Token[] $tokens
     * @return Token[]
     * @throws \Exception If not found left parentheses in stack (@see PostfixNotation::popTokensWhileNotFoundLeftParentheses)
     */
    public function convert(array $tokens)
    {
        $stack = new \SplStack();
        $output = [];

        while (count($tokens) > 0) {
            $current = array_shift($tokens);
            if ($current instanceof NumToken) {
                $output[] = $current;
            } elseif ($current instanceof LParToken) {
                $stack->push($current);
            } elseif ($current instanceof RParToken) {
                $this->popTokensWhileNotFoundLeftParentheses($stack, $output);

            } elseif ($current instanceof OperatorToken) {
                $this->popTokensWhileOperatorMustGoBeforeCurrent($stack, $current, $output);
            }
        }

        while ($stack->count() > 0) {
            $current = $stack->pop();
            if (!($current instanceof OperatorToken)) {
                throw new \Exception('Excess parentheses at position ' . $current->getPosition());
            }
            $output[] = $current;
        }

        return $output;
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
            throw new \Exception();
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
    private function popTokensWhileOperatorMustGoBeforeCurrent(\SplStack $stack, $current, &$output)
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