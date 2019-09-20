<?php
declare(strict_types=1);

namespace Ohchiko\Randomizer\Exceptions;

use Exception;

class UnsupportedLanguageException extends Exception
{
    protected $code = 0;
    protected $message = 'Language is not (yet) supported.';
    protected $title = 'Unsupported Language';
    protected $description = 'The given language is eiter not yet supported or unavailable.';

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if ($message !== null) {
            $this->message = $message;
        }

        parent::__construct($this->message, $this->code, $previous);
    }
}
