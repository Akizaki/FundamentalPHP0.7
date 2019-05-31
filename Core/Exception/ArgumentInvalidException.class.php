<?php

/**
 * ArgumentInvalidException::InvalidArgumentException
 * 
 * Exception thrown if method argument is invalid
 * 
 * @category  FundamentalPHP
 * @package   Core/Exception
 */
class ArgumentInvalidException extends InvalidArgumentException {

    /**
     * 
     * @param string $method
     * @param mixed $argument
     * @param int $argumentNo
     * @param string $needed
     */
    public function __construct($method, $argument, $argumentNo, $needed = "")
    {
        $neededMsg = '';
        if ($needed != null)
            $neededMsg = ' must be an ' . $needed;
        $givenType = gettype($argument);
        if ($givenType == 'object')
            $givenType = get_class($argument);
        parent::__construct(sprintf('Argument %s passed to %s%s, %s given.', $argumentNo, $method, $neededMsg, $givenType));
    }

}
