<?php
/**
 * Randomizer - a php helper to generate a random string
 *
 * @author       Gregorio Chiko <chiko150@outlook.com>
 * @copyright    2019 Gregorio Chiko
 * @license      https://github.com/ohchiko/randomizer/blob/master/LICENSE
 * @version      v0.1.0
 * @package      randomizer
 */
declare(strict_types=1);

namespace Ohchiko\Randomizer;

use Ohchiko\Randomizer\Exceptions\UnsupportedLanguageException;
use Ohchiko\Randomizer\Exceptions\UnreadableWordsetFileException;
use Ohchiko\Randomizer\Exceptions\IndexOutOfRangeException;
use Exception;
use InvalidArgumentException;
use ReflectionClass;
use SplFileObject;

class Randomizer
{
    const LANG_ID = 'id';
    const LANG_EN = 'en';

    protected static $language = null;
    protected static $defaultLanguage = self::LANG_ID;
    protected static $wordset = null;
    protected static $wordsetDir = 'resources/wordsets/';
    protected static $punctset = null;
    protected static $defaultPunctset = [
        "!", "@", "#", "$", "%", "^", "&", "*", "(", ")"
    ];

    /**
     * Generate random string
     * this is the main function of the class
     *
     * @param int|3 $wordCount
     * @param int|3 $punctCount
     *
     * @return string
     */
    public static function generate(int $wordCount = 3, int $punctCount = 3): string
    {
        if ($wordCount < 1 || $punctCount < 1) {
            throw new InvalidArgumentException("The minimal required amount to be randomized is 1. Requested words: $wordCount. Requested punctuations: $punctCount");
        }

        $words = self::getRandomWordsFromWordset($wordCount);
        $punctuations = self::getRandomPunctuationsFromPunctset($punctCount);

        $words = lcfirst(preg_replace("/\W/", "", ucwords(join(" ", array_slice($words, 0, $wordCount)))));
        $punctuations = join("", array_slice($punctuations, 0, $punctCount));
        return $words . $punctuations;
    }

    /**
     * Set the language to be used in randomized string
     *
     * @param string $language
     *
     * @return void
     */
    public static function setLanguage(string $language): void
    {
        if (self::languageIsAvailable($language)) {
            self::$language = $language;
        } else {
            throw new UnsupportedLanguageException("Language '$language' is not (yet) supported/available.");
        }
    }

    /**
     * Get the language to be used in randomized string
     *
     * @return string
     */
    public static function getLanguage(): string
    {
        if (self::$language) {
            return self::$language;
        }

        return self::$defaultLanguage;
    }

    /**
     * Get languages that available in this class
     *
     * @return array
     */
    public static function getAvailableLanguages(): array
    {
        $class = new ReflectionClass(self::class);

        return $class->getConstants();
    }

    /**
     * Set a custom wordset to randomized
     *
     * @param array $wordset
     *
     * @return void
     */
    public static function setCustomWordset(array $wordset): void
    {
        self::$wordset = $wordset;
    }

    /**
     * Get the wordset to be randomized
     *
     * @return array|null
     */
    public static function getCustomWordset(): ?array
    {
        return self::$wordset;
    }

    /**
     * Get the default wordset
     * Warning: this method might return a huge size of array
     *          based on the wordset file
     *
     * @return array
     */
    public static function getDefaultWordset(): array
    {
        $wordset = [];
        $wordsetFile = self::getWordsetFile();

        while (!$wordsetFile->eof()) {
            if ($word = trim($wordsetFile->fgets())) {
                $wordset[] = $word;
            }
        }

        return $wordset;
    }

    /**
     * Set a custom wordset to randomized
     *
     * @param array $wordset
     *
     * @return void
     */
    public static function setCustomPunctset(array $punctset): void
    {
        self::$punctset = $punctset;
    }

    /**
     * Get the punctset to be randomized
     *
     * @return array|null
     */
    public static function getCustomPunctset(): ?array
    {
        return self::$punctset;
    }

    /**
     * Get the default punctset
     *
     * @return array
     */
    public static function getDefaultPunctset(): array
    {
        return self::$defaultPunctset;
    }

    /**
     * Check if give $language is available in this class
     *
     * @param string $language
     *
     * @return bool
     */
    protected static function languageIsAvailable(string $language): bool
    {
        return in_array($language, self::getAvailableLanguages());
    }

    /**
     * Get the wordset file based on language
     *
     * @return SplFileObject
     */
    protected static function getWordsetFile(): SplFileObject
    {
        $filename = self::getWordsetDirectory() . self::getLanguage() . '_wordset';

        try {
            $file = new SplFileObject($filename, 'r');
        } catch (Exception $e) {
            throw new UnreadableWordsetFileException('You might have not enough permission to access this wordset file.');
        }

        return $file;
    }

    /**
     * Get the wordset directory
     *
     * @return string
     */
    protected static function getWordsetDirectory(): string
    {
        return dirname(dirname(__FILE__)) . '/' . self::$wordsetDir;
    }

    /**
     * Count the amount of wordset
     *
     * @return int
     */
    protected static function getCountWordset(): int
    {
        if ($wordset = self::getCustomWordset()) {
            return count($wordset);
        }

        $wordsetFile = self::getWordsetFile();
        $wordsetFile->seek(PHP_INT_MAX);

        return $wordsetFile->key();
    }

    /**
     * Count the amount of wordset
     *
     * @return int
     */
    protected static function getCountPunctset(): int
    {
        if ($punctset = self::getCustomPunctset()) {
            return count($punctset);
        }

        return count(self::getDefaultPunctset());
    }

    /**
     * Get a random indexes
     *
     * @param int $amount
     * @param array $range
     *
     * @return array
     */
    protected static function getUniqueRandomIndexes(int $amount, array $range): array
    {
        if ($amount < 1) {
            throw new InvalidArgumentException("Amount requested should not be a zero or negative integer.");
        }

        if ($amount > ($range['max'] - $range['min'] + 1)) {
            $min = $range['min'];
            $max = $range['max'];

            throw new IndexOutOfRangeException("The requested amount is exceed the available data. Requested: $amount. Range: $min - $max");
        }

        $range = array_merge([ 'min' => 1, 'max' => 1 ], $range);

        if ($range['min'] < 0 || $range['max'] < 0) {
            throw new InvalidArgumentException("Range must be a positive integer.");
        }

        $indexes = [];

        while (count($indexes) != $amount) {
            $x = rand($range['min'], $range['max']);

            if (!in_array($x, $indexes)) {
                $indexes[] = $x;
            }
        }

        return $indexes;
    }

    /**
     * Get random words from wordset
     *
     * @param int $amount
     *
     * @return array
     */
    protected static function getRandomWordsFromWordset(int $amount): array
    {
        if ($amount < 1) {
            throw new InvalidArgumentException("Amount requested should not be a zero or negative integer.");
        }

        $count = self::getCountWordset() - 1;
        $indexes = self::getUniqueRandomIndexes($amount, [ 'min' => 0, 'max' => $count ]);
        $words = [];

        if ($customWordset = self::getCustomWordset()) {
            foreach ($indexes as $i) {
                $words[] = $customWordset[$i];
            }
        } else {
            $wordsetFile = self::getWordsetFile();

            foreach ($indexes as $i) {
                $wordsetFile->seek($i);
                $words[] = $wordsetFile->current();
            }
        }

        return $words;
    }

    /**
     * Get random punctuations
     *
     * @param int $amount
     *
     * @return array
     */
    protected static function getRandomPunctuationsFromPunctset(int $amount): array
    {
        $count = self::getCountPunctset();
        $indexes = self::getUniqueRandomIndexes($amount, [ 'min' => 0, 'max' => ($count - 1) ]);
        $punctset = self::getCustomPunctset() ?: self::getDefaultPunctset();
        $puntuations = [];

        foreach ($indexes as $i) {
            $punctuations[] = $punctset[$i];
        }

        return $punctuations;
    }
}
