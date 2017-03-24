<?php
/**
 * Created by PhpStorm.
 * User: vershinin
 * Date: 24.03.2017
 * Time: 10:43
 */

namespace app\models;

/**
 * Class Lexeme
 *
 * @package app\models
 */
class Lexeme
{
    /**
     * Lexeme value
     *
     * @return string
     */
    private $_value;
    /**
     * Lexeme position in expression
     *
     * @return integer
     */
    private $_position;

    /**
     * Lexeme constructor.
     *
     * @param string $value Lexeme value
     * @param integer $position Lexeme position in expression
     */
    public function __construct($value, $position)
    {
        $this->_value = $value;
        $this->_position = (int) $position;
    }

    /**
     * Lexeme value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Lexeme position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->_position;
    }
}