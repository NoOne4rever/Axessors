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
class ConditionsProcessor extends TokenProcessor
{
    /**
     * Creates list of conditions for input data.
     *
     * @return string[] conditions
     */
    public function processInputData(): array
    {
        return $this->makeConditionsTree($this->input);
    }

    /**
     * Creates list of conditions for output data.
     *
     * @return string[] conditions
     */
    public function processOutputData(): array
    {
        return $this->makeConditionsTree($this->output);
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
        $conditions = $injProcessor->addSlashes('|&')->get();
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
                    $result[] = $injProcessor->resolveClassNames($this->namespace)->processThis()->wrapWithClosure()->get();
                } else {
                    $result[$number][] = $token;
                }
            }
        }
        return $result;
    }
}