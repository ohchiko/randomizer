<?php
declare(strict_types=1);

namespace Ohchiko\Randomizer\Exceptions;

use Exception;

class UnreadableWordsetFileException extends Exception
{
    protected $code = 0;
    protected $message = 'Wordset file is unreadable.';
    protected $title = 'Unreadabel Wordset File';
    protected $description = 'You might have not enough permission to access this wordset file.';

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if ($message !== null) {
            $this->message = $message;
        }

        parent::__construct($this->message, $this->code, $previous);
    }
}
