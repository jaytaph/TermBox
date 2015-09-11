<?php

namespace JayTaph\TermBox\Terminal;

use JayTaph\TermBox\Exception\TerminalFunctionNotFoundException;
use JayTaph\TermBox\Exception\TerminalKeyNotFoundException;

abstract class Terminal implements TerminalInterface {

    abstract public function getKeys();
    abstract public function getFunctions();
    abstract public function getName();

    /**
     * @param $function
     * @return mixed
     */
    public function getFunction($function)
    {
        $functions = $this->getFunctions();
        if (isset($functions[$function])) {
            return $functions[$function];
        }

        throw new TerminalFunctionNotFoundException();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getKey($key)
    {
        $keys = $this->getKeys();
        if (isset($keys[$key])) {
            return $keys[$key];
        }

        throw new TerminalKeyNotFoundException();
    }
}
