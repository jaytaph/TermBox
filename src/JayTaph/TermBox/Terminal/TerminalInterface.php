<?php

namespace JayTaph\TermBox\Terminal;

interface TerminalInterface {

    public function getFunction($function);
    public function getKey($key);
    public function getName();

}
