<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

/**
 * Class TokenProcessor.
 *
 * Processes token string and makes input and output data.
 *
 * @package NoOne4rever\Axessors
 */
abstract class TokenProcessor
{
    /** @var string tokens that belong to setter */
    protected $input;
    /** @var string tokens that belong to getter */
    protected $output;
    /** @var string class namespace */
    protected $namespace;

    /**
     * TokenProcessor constructor.
     *
     * @param string $in input token
     * @param string $out output token
     * @param string $ns class namespace
     */
    public function __construct(string $in, string $out, string $ns)
    {
        $this->input = $in;
        $this->output = $out;
        $this->namespace = $ns;
    }

    /**
     * Processes tokens that belong to setter.
     *
     * @return array token data for setter
     */
    abstract public function processInputData(): array;

    /**
     * Processes tokens that belong to getter.
     *
     * @return array token data for getter
     */
    abstract public function processOutputData(): array;
}