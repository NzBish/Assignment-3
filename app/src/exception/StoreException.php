<?php

namespace ktc\a2\Exception;

/**
 * Class SearchException
 *
 * @package ktc/a2
 * @author  K. Dempsey
 * @author  T. Crompton
 * @author  C. Bishop
 */

class StoreException extends \Exception
{
    /**
     * SearchException constructor
     *
     * Creates a SearchException and sets $message if $code is a known error
     *
     * @param int $code Sets $code in the created object
     * @param string $message Sets $message in the created object
     */
    public function __construct($code = 0, $message = "")
    {
        switch ($code) {
            case 4:
                $message = 'Invalid username or password';
                break;
            case 5:
                $message = 'Username already exists';
                break;
            case 6:
                $message = 'Failed to hash entered password';
                break;
            case 7:
                $message = 'Failed to create user';
                break;
            case 8:
                $message = 'User is not logged in';
                break;
            case 9:
                $message = 'Password does not meet requirements';
                break;
            default:
                $code = 99;
                break;
        }

        parent::__construct($message, $code);
    }
}
