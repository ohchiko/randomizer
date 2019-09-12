<?php

namespace Ohchiko\Randomizer;

class Randomizer
{
    /**
     * Define language constants
     *
     */
    const LANG_ID = 'id';
    const LANG_EN = 'en';

    /**
     * Variable to store wordset
     *
     */
    protected static $wordset;

    /**
     * The wordset directory name inside the project
     *
     */
    protected static $wordsetDir = 'resources/wordsets/';

    /**
     * Set list of punctuations.
     *
     */
    protected static $punctset = [
        "!", "@", "#", "$", "%", "^", "&", "*", "(", ")"
    ];

    /**
     * Generate random string
     *
     * @param string $lang
     * @return string
     */
    public static function generate($lang = self::LANG_ID): string
    {
        // Set the wordset language
        self::setWordset($lang);

        // Cycle through the wordset
        for ($i = (count(self::$wordset) - 1); $i > 0; $i--)
        {
            // Get random index
            $x = floor(rand(0, $i));

            // Swap the words a.k.a shuffle the wordset
            [self::$wordset[$i], self::$wordset[$x]] = [self::$wordset[$x], self::$wordset[$i]];
        }

        // Retrieve the first three words of the wordset
        $words = preg_replace("/\W/", "", ucwords(join(" ", array_slice(self::$wordset, 0, 3))));
        $words = lcfirst($words);

        // Retrieve a single punctuation
        $punct = self::$punctset[floor(rand(0, count(self::$punctset)))];

        return $words . $punct;
    }

    /**
     * Set the wordset based on given language
     *
     * @param string $lang
     * @return void
     */
    protected static function setWordset($lang): void
    {
        $filename = $lang . '_wordset';

        $wordsetFile = self::getWordsetRealDirectory() . $filename;

        self::$wordset = file($wordsetFile);

        return;
    }

    /**
     * Retrieve wordset real directory
     *
     * @return string
     */
    protected static function getWordsetRealDirectory(): string
    {
        return dirname(dirname(__FILE__)) . '/' . self::$wordsetDir;
    }
}
