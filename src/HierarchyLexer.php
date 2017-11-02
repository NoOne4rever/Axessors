<?php

namespace Axessors;

use Axessors\Exceptions\InternalError;

class HierarchyLexer extends Lexer
{
    private $expectations;
    private $requiredItems;

    public function __construct(\ReflectionClass $reflection)
    {
        parent::__construct($reflection);
        if ($reflection->isInterface()) {
            $this->expectations = [
                '{^#}',
                '{^public}',
                '{^function}',
                '{^[a-zA-Z_][a-zA-Z0-9_]*}'
            ];
            $this->requiredItems = [0, 1, 2, 3];
        } elseif ($reflection->isAbstract()) {
            $this->expectations = [
                '{^#}',
                '{^abstract}',
                '{^(public|protected)}',
                '{^function}',
                '{^[a-zA-Z_][a-zA-Z0-9_]*}'
            ];
            $this->requiredItems = [0, 1, 2, 3, 4];
        } else {
            throw new InternalError("\"{$reflection->name}\" is not an interface or abstract class");
        }
    }

    public function getMethods(): array
    {
        $methods = [];
        for ($i = $this->startLine; $i <= $this->endLine; ++$i) {
            $this->readLine();
            if (!$this->isAxsMethod()) {
                continue;
            }
            $method = $this->getMethod();
            if ($this->reflection->isInterface()) {
                $accessModifier = $method[1];
                $methodName = $method[3];
            } else {
                $accessModifier = $method[2];
                $methodName = $method[4];
            }
            $methods[$accessModifier][] = $methodName;
        }
        return $methods;
    }

    private function getMethod(): array
    {
        return $this->parse(
            $this->currentLine,
            $this->expectations,
            $this->requiredItems
        );
    }

    private function isAxsMethod(): bool
    {
        return preg_match('{^\s*#}', $this->currentLine);
    }
}
