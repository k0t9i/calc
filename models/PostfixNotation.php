<?php

namespace app\models;

use app\models\tokens\OperatorToken;
use app\models\tokens\LParToken;
use app\models\tokens\NumToken;
use app\models\tokens\RParToken;
use app\models\tokens\Token;

class PostfixNotation
{
    /**
     * @param Token[] $tokens
     * @return array
     * @throws \Exception
     */
    public function convert(array $tokens)
    {
        $stack = new \SplStack();
        $output = [];

        while (count($tokens) > 0) {
            $token = array_shift($tokens);
            if ($token instanceof NumToken) {
                $output[] = $token;
            } elseif ($token instanceof LParToken) {
                $stack->push($token);
            } elseif ($token instanceof RParToken) {
                $current = $stack->pop();
                while (!($current instanceof LParToken)) {
                    $output[] = $current;
                    if ($stack->count() == 0) {
                        break;
                    }
                    $current = $stack->pop();
                }
                if (!($current instanceof LParToken)) {
                    throw new \Exception();
                }
            } elseif ($token instanceof OperatorToken) {
                if ($stack->count() > 0) {
                    $top = $stack->top();
                    while ($top instanceof OperatorToken && $token->isAssociativeLeft() && $token->comparePriority($top) <= 0 ||
                        $token->isAssociativeRight() && $token->comparePriority($top) <  0) {
                        $output[] = $stack->pop();
                        if ($stack->count() == 0) {
                            break;
                        }
                        $top = $stack->top();
                    }
                }
                $stack->push($token);
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
}