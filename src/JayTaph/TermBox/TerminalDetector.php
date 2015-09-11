<?php

namespace JayTaph\TermBox;

use JayTaph\TermBox\Exception\TerminalNotDetectedException;
use JayTaph\TermBox\Terminal;
use JayTaph\TermBox\Terminal\TerminalInterface;

class TerminalDetector {

    /** @var TerminalInterface[] */
    protected $terminals = array();

    public function __construct() {
        $this->addTerminal(new Terminal\Linux());
        $this->addTerminal(new Terminal\XTerm());
        // ...
    }

    public function addTerminal(TerminalInterface $terminal)
    {
        $this->terminals[] = $terminal;
    }

    public function detect()
    {
        $term_name = getenv("TERM");

        return $this->detectTerminalFromName($term_name);
    }

    protected function detectTerminalFromName($term_name)
    {
        foreach ($this->terminals as $terminal) {
            if (strcasecmp($terminal->getName(), $term_name) === 0) {
                return $terminal;
            }
            if (strstr($terminal->getName(), $term_name) !== false) {
                return $terminal;
            }
        }

        throw new TerminalNotDetectedException();
    }
}
