<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

/**
 * Class ConditionsProcessor.
 * 
 * Processes Axessors conditions.
 * 
 * @package NoOne4rever\Axessors
 */
class ConditionsProcessor
{
    /** @var string conditions for setter */
    private $inputConditions;
    /** @var string conditions for getter */
    private $outputConditions;
    /** @var string class namespace */
    private $namespace;

    /**
     * ConditionsProcessor constructor.
     * 
     * @param string $in input conditions
     * @param string $out output conditions
     * @param string $namespace class namespace
     */
    public function __construct(string $in, string $out, string $namespace)
    {
        $this->inputConditions = $in;
        $this->outputConditions = $out;
        $this->namespace = $namespace;
    }

    /**
     * Creates list of conditions for input data.
     *
     * @return string[] conditions
     */
    public function processInputConditions(): array
    {
        return $this->makeConditionsTree($this->inputConditions);
    }

    /**
     * Creates list of conditions for output data.
     *
     * @return string[] conditions
     */
    public function processOutputConditions(): array
    {
        return $this->makeConditionsTree($this->outputConditions);
    }

    /**
     * Creates list of conditions from a string of conditions definition.
     *
     * @param string $conditions conditions
     * @return array conditions
     */
    private function explodeConditions(string $conditions): array
    {
        if ($conditions === '') {
            return [];
        }
        $result = [];
        $injProcessor = new InjectedStringSuit($conditions);
        $conditions = $injProcessor->addSlashes('|&');
        $conditions = preg_split('{\s*\|\|\s*}', $conditions);
        foreach ($conditions as $condition) {
            $result[] = preg_split('{\s*&&\s*}', $condition);
        }
        return $this->rmSlashes($result);
    }

    /**
     * Removes slashes from conditions.
     * 
     * @param array $conditions conditions
     * @return array processed conditions
     */
    private function rmSlashes(array $conditions): array 
    {
        foreach ($conditions as $number => &$complexCondition) {
            if (is_array($complexCondition)) {
                foreach ($complexCondition as $num => &$condition) {
                    $condition = stripcslashes($condition);
                }
            } else {
                $complexCondition = stripcslashes($complexCondition);
            }
        }
        return $conditions;
    }

    /**
     * Makes tree of conditions.
     *
     * @param string $conditions string with conditions definition
     * @return array tree of conditions
     */
    private function makeConditionsTree(string $conditions): array
    {
        $result = [];
        $conditions = $this->explodeConditions($conditions);
        foreach ($conditions as $number => $condition) {
            foreach ($condition as $token) {
                $injProcessor = new InjectedStringSuit($token);
                if (count($condition) === 1) {
                    $result[] = $injProcessor->resolveClassNames($this->namespace);
                } else {
                    $result[$number][] = $token;
                }
            }
        }
        return $result;
    }
}