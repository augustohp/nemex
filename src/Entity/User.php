<?php

namespace Nemex\Entity;

use InvalidArgumentException as Argument;
use Respect\Validation\Validator as v;

class User
{
    private $name = null;

    public function __construct($userName)
    {
        try {
            v::string()
             ->alnum('_-.')
             ->noWhitespace()
             ->notEmpty()
             ->length(1,50, true)
             ->assert($userName);
        } catch (Argument $previousException) {
            $message = sprintf('User name is invalid. (%s)', $userName);
            throw new Argument($message, 0, $previousException);
        }

        $this->name = $userName;
    }

    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
