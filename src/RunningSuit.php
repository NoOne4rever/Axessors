<?php
/**
 * This file is a part of "Axessors" library.
 *
 * @author <NoOne4rever@gmail.com>
 * @license GPL
 */

namespace NoOne4rever\Axessors;

/**
 * Class RunningSuit.
 * 
 * Stores running configuration.
 * 
 * @package NoOne4rever\Axessors
 */
abstract class RunningSuit
{
    public const INPUT_MODE = 4;
    public const OUTPUT_MODE = 8;
    
    /** @var object|null object */
    protected $object;
    /** @var PropertyData property data */
    protected $propertyData;
    /** @var string class */
    protected $class;
    /** @var int running mode */
    protected $mode;
    
    public function __construct(int $mode, PropertyData $data, string $class, $object = null)
    {
        $this->mode = $mode;
        $this->propertyData = $data;
        $this->class = $class;
        $this->object = $object;
    }
}