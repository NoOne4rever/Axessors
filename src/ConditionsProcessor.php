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
     * @return string conditions
     */
    public function processInputData(): string
    {
        return $this->makePhpConditions($this->input);
    }

    /**
     * Creates list of conditions for output data.
     *
     * @return string conditions
     */
    public function processOutputData(): string
    {
        return $this->makePhpConditions($this->output);
    }

    /**
     * Turns Axessors conditions into PHP code.
     *
     * @param string $axsConditions Axessors conditional statement
     *
     * @return string
     */
    private function makePhpConditions(string $axsConditions): string
    {
        if ($axsConditions === '') {
            return '`true`';
        }
        $injProcessor = new InjectedStringSuit($axsConditions);
        $axsConditions = $injProcessor->resolveClassNames($this->namespace)->processThis()->wrapWithClosure()
            ->addSlashes('<>!=')
            ->get();
        $axsConditions = preg_replace_callback(
            '/(((>|<)=?)|(=|!)=)\s*\d+/',
            function (array $matches): string {
                return sprintf('\NoOne4rever\Axessors\ConditionsRunner::count($var) %s', $matches[0]);
            },
            $axsConditions
        );
        $axsConditions = preg_replace_callback(
            '/\d+\.\.\d+/',
            function (array $matches): string {
                list($min, $max) = explode('..', $matches[0]);
                return sprintf('\NoOne4rever\Axessors\ConditionsRunner::count($var) >= %d'
                    . ' && \NoOne4rever\Axessors\ConditionsRunner::count($var) <= %d', $min, $max);
            },
            $axsConditions
        );
        $axsConditions = preg_replace_callback(
            '/\\\\[<>!=]/',
            function (array $matches): string {
                return substr($matches[0], 1);
            },
            $axsConditions
        );
        $axsConditions = preg_replace_callback(
            '/`[^`]+`/',
            function (array $matches): string {
                $subject = substr($matches[0], 1, strlen($matches[0]) - 2);
                return sprintf('(function($var){return %s;})($var)', $subject);
            },
            $axsConditions
        );
        return "`$axsConditions`";
    }
}