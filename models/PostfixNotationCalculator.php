<?php

namespace app\models;

use app\models\tokens\NumToken;
use app\models\tokens\OperatorToken;

class PostfixNotationCalculator
{
    public function calculate(array $tokens)
    {
        $result = 0;
        $stack = new \SplStack();

        while (count($tokens) > 0) {
            $current = array_shift($tokens);
            if ($current instanceof NumToken) {
                $stack->push($current);
            } else if ($current instanceof OperatorToken) {
                $args = [];
                for ($i = 0; $i < $current->argsCount(); $i++) {
                    if ($stack->count() == 0) {
                        throw new CalculateSyntaxException('Syntax error at position ' . $current->getPosition() . ' near lexeme "' . $current->getLexeme() . '"');
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