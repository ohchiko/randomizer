<?php
declare(strict_types=1);

namespace Ohchiko\Randomizer\Exceptions;

use InvalidArgumentException;

class IndexOutOfRangeException extends InvalidArgumentException
{
    protected $code = 0;
    protected $message = 'Index(es) is out of range.';
    protected $title = 'Index Out Of Range';
    protected $description = 'The supplied or produced index(es) is out of range.';

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if ($message !== null) {
            $this->message = $message;
        }

        parent::__construct($this->message, $this->code, $previous);
    }
}
