<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Ohchiko\Randomizer\Randomizer;
use Ohchiko\Randomizer\Exceptions\UnsupportedLanguageException;
use Ohchiko\Randomizer\Exceptions\UnreadableWordsetFileException;
use Ohchiko\Randomizer\Exceptions\IndexOutOfRangeException;

final class RandomizerTest extends TestCase
{
    protected $wordset = [
        "aba",
        "abad",
        "abadi",
        "abadiah",
        "abah",
        "abai",
        "abaimana",
        "abaka",
        "abaktinal",
        "abakus",
        "abal-abal",
        "aban",
        "abang",
        "abangan",
        "abangga",
        "abar",
        "abatoar",
        "abau",
        "abdas",
        "abdi",
        "abdikasi",
        "abdomen",
        "abdominal",
        "abdu",
        "abduksi",
        "abduktor",
        "abece",
        "aben",
        "aberasi",
        "abet",
        "abian",
        "abid",
        "abidin",
        "abilah",
        "abing",
        "abiogenesis",
        "abiosfer",
        "abiotik",
        "abis",
        "abisal",
        "abiseka",
        "abiturien",
        "abjad",
        "abjadiah",
        "ablasi",
        "ablaut",
        "ablepsia",
        "abnormal",
        "abnormalitas",
        "abnus",
        "aboi",
        "abolisi",
        "abon",
        "abonemen",
        "abong-abong",
        "aborsi",
        "abortif",
        "abortiva",
        "abortus",
        "abrak",
        "abrakadabra",
        "abrar",
        "abras",
        "abrasi",
        "abreaksi",
        "abrek",
        "abreviasi",
        "abrikos",
        "abrit-abrit",
        "abrosfer",
        "absah",
        "absen",
        "absensi",
        "absensia",
        "absente",
        "absenteisme",
        "abses",
        "absis",
        "absolusi",
        "absolut",
        "absolutisme",
        "absonan",
        "absorb",
        "absorben",
        "absorbir",
        "absorpsi",
        "absorpsiometer",
        "absorptif",
        "abstain",
        "abstinensi",
        "abstrak",
        "abstraksi",
        "absurd",
        "absurdisme",
        "abtar",
        "abu",
        "abuan",
        "abuh",
        "abuk",
        "abulhayat",
    ];

    protected $punctset = [
        "!", "@", "#", "$", "%", "^", "&", "*", "(", ")"
    ];

    protected function setUp(): void
    {
        self::getProperty('language')->setValue(null);
        self::getProperty('wordset')->setValue(null);
        self::getProperty('punctset')->setValue(null);
    }

    protected static function getMethod($name): ReflectionMethod
    {
        $reflection = new ReflectionClass(Randomizer::class);

        $method = $reflection->getMethod($name);

        $method->setAccessible(true);

        return $method;
    }

    protected static function getProperty($name): ReflectionProperty
    {
        $reflection = new ReflectionClass(Randomizer::class);

        $property = $reflection->getProperty($name);

        $property->setAccessible(true);

        return $property;
    }

    protected static function getConstants(): array
    {
        $reflection = new ReflectionClass(Randomizer::class);

        $constants = $reflection->getConstants();

        return $constants;
    }

    public function testCanGenerateRandomString(): void
    {
        $random = Randomizer::generate(1, 3);

        $this->assertIsString($random);
        $this->assertTrue(preg_match("/\w+[^\d\w]{3}/", $random) === 1);
    }

    public function testCannotGenerateRandomStringIfAmountRequestedIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Randomizer::generate(0, -2);
    }

    public function testCanSetLanguageWithConstant(): void
    {
        Randomizer::setLanguage(Randomizer::LANG_EN);

        $this->assertEquals(self::getProperty('language')->getValue(), Randomizer::LANG_EN);
    }

    public function testCanSetLanguageWithString(): void
    {
        Randomizer::setLanguage('en');

        $this->assertEquals(self::getProperty('language')->getValue(), 'en');
    }

    public function testCannotSetLanguageWithUnavailableLanguage()
    {
        $this->expectException(UnsupportedLanguageException::class);

        Randomizer::setLanguage('sp');
    }

    public function testCanGetLanguage(): void
    {
        $language = Randomizer::getLanguage();

        $this->assertEquals(Randomizer::LANG_ID, $language);
    }

    public function testCanGetAvailableLanguages()
    {
        $languages = Randomizer::getAvailableLanguages();

        $this->assertSame(self::getConstants(), $languages);
    }

    public function testCanSetCustomWordset(): void
    {
        $wordset = ["oke", "deh", "jikalau", "begitu", "adanya", "baiklah"];

        Randomizer::setCustomWordset($wordset);

        $this->assertSame($wordset, self::getProperty('wordset')->getValue());
    }

    public function testCanGetCustomWordset(): void
    {
        $wordset = ["oke", "deh", "jikalau", "begitu", "adanya", "baiklah"];

        Randomizer::setCustomWordset($wordset);

        $customWordset = self::getProperty('wordset')->getValue();

        $this->assertSame($wordset, $customWordset);
    }

    public function testCanGetNullCustomWordsetIfNotSet(): void
    {
        $this->assertNull(Randomizer::getCustomWordset());
    }

    public function testCanGetDefaultWordset(): void
    {
        $count = count($this->wordset);
        $defaultWordset = array_slice(Randomizer::getDefaultWordset(), 0, 100);

        $this->assertSame($this->wordset, $defaultWordset);
        $this->assertCount($count, $defaultWordset);
    }

    public function testCanSetCustomPunctset(): void
    {
        $punctset = ["&", "@", "%", "<", "[", "+"];

        Randomizer::setCustomPunctset($punctset);

        $this->assertSame($punctset, self::getProperty('punctset')->getValue());
    }

    public function testCanGetCustomPunctset(): void
    {
        $punctset = ["&", "@", "%", "<", "[", "+"];

        Randomizer::setCustomPunctset($punctset);

        $this->assertSame($punctset, Randomizer::getCustomPunctset());
    }

    public function testCanGetNullCustomPunctsetIfNotSet(): void
    {
        $this->assertNull(Randomizer::getCustomPunctset());
    }

    public function testCanGetDefaultPunctset(): void
    {
        $defaultPunctset = Randomizer::getDefaultPunctset();

        $this->assertIsArray($defaultPunctset);
        $this->assertSame($this->punctset, $defaultPunctset);
    }

    public function testCanCheckIfLanguageIsAvailable(): void
    {
        $available = self::getMethod('languageIsAvailable')->invokeArgs(null, [Randomizer::LANG_EN]);

        $this->assertIsBool($available);
        $this->assertTrue($available);
    }

    public function testCanGetWordsetFileObject(): void
    {
        $source = new SplFileObject(dirname(dirname(__FILE__)) . "/resources/wordsets/id_wordset", "r");
        $file = self::getMethod('getWordsetFile')->invoke(null);

        $this->assertInstanceOf(SplFileObject::class, $file);
        $this->assertEquals($source, $file);
    }

    public function testCannotGetWordsetFileObjectIfNotReadable(): void
    {
        $this->expectException(UnreadableWordsetFileException::class);

        self::getProperty('language')->setValue('unreadablee');
        $file = self::getMethod('getWordsetFile')->invoke(null);

        var_dump($file);
    }

    public function testCanGetWordsetDirectory(): void
    {
        $source = dirname(dirname(__FILE__)) . '/' . self::getProperty('wordsetDir')->getValue();

        $this->assertEquals($source, self::getMethod('getWordsetDirectory')->invoke(null));
    }

    public function testCanCountWordset(): void
    {
        $source = count($this->wordset);

        Randomizer::setCustomWordset($this->wordset);

        $count = self::getMethod('getCountWordset')->invoke(null);

        $this->assertEquals($source, $count);
    }

    public function testCanCountPunctset(): void
    {
        $source = count($this->punctset);
        $count = self::getMethod('getCountPunctset')->invoke(null);

        $this->assertEquals($source, $count);
    }

    public function testCanGetUniqueRandomIndexes(): void
    {
        $amount = 3;
        $range = [ 'min' => 1, 'max' => 3 ];

        $random = self::getMethod('getUniqueRandomIndexes')->invokeArgs(null, [$amount, $range]);

        $this->assertIsArray($random);

        foreach ($random as $index) {
            $this->assertIsInt($index);
            $this->assertTrue($index >= $range['min'] && $index <= $range['max']);
        }
    }

    public function testCannotGetUniqueRandomIndexesIfAmountRequestedIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $amount = -3;
        $range = [ 'min' => 1, 'max' => 3 ];

        self::getMethod('getUniqueRandomIndexes')->invokeArgs(null, [$amount, $range]);
    }

    public function testCannotGetUniqueRandomIndexesIfAmountRequestedIsOutOfRange(): void
    {
        $this->expectException(IndexOutOfRangeException::class);

        $amount = 5;
        $range = [ 'min' => 1, 'max' => 3 ];

        self::getMethod('getUniqueRandomIndexes')->invokeArgs(null, [$amount, $range]);
    }

    public function testCannotGetUniqueRandomIndexesIfRangeIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $amount = 3;
        $range = [ 'min' => -2, 'max' => 3 ];

        self::getMethod('getUniqueRandomIndexes')->invokeArgs(null, [$amount, $range]);
    }

    public function testCanGetRandomWordsFromWordset(): void
    {
        $amount = 3;

        $random = self::getMethod('getRandomWordsFromWordset')->invokeArgs(null, [$amount]);

        $this->assertIsArray($random);
        $this->assertCount($amount, $random);

        foreach ($random as $word) {
            $this->assertIsString($word);
        }
    }

    public function testCanGetRandomWordsFromCustomWordset(): void
    {
        $amount = 3;
        $wordset = ["saya", "oke", "juga"];

        Randomizer::setCustomWordset($wordset);

        $random = self::getMethod('getRandomWordsFromWordset')->invokeArgs(null, [$amount]);

        $this->assertIsArray($random);
        $this->assertCount($amount, $random);
        $this->assertSame($wordset, array_intersect($wordset, $random));

        foreach($random as $word) {
            $this->assertIsString($word);
        }
    }

    public function testCannotGetRandomWordsFromWordsetIfAmountRequestedIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $amount = -2;

        self::getMethod('getRandomWordsFromWordset')->invokeArgs(null, [$amount]);
    }

    public function testCanGetRandomPunctuationsFromPunctset(): void
    {
        $amount = 3;

        $random = self::getMethod('getRandomWordsFromWordset')->invokeArgs(null, [$amount]);

        $this->assertIsArray($random);
        $this->assertCount($amount, $random);

        foreach ($random as $punctuation) {
            $this->assertIsString($punctuation);
        }
    }

    public function testCanGetRandomPunctuationsFromCustomPunctset(): void
    {
        $amount = 3;
        $punctset = ["(", "%", "#"];

        Randomizer::setCustomPunctset($punctset);

        $random = self::getMethod('getRandomPunctuationsFromPunctset')->invokeArgs(null, [$amount]);

        $this->assertIsArray($random);
        $this->assertCount($amount, $random);
        $this->assertSame($punctset, array_intersect($punctset, $random));

        foreach ($random as $punctuation) {
            $this->assertIsString($punctuation);
        }
    }

    public function testCannotGetRandomPunctuationsFromPunctsetIfAmountRequestedIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $amount = -2;

        self::getMethod('getRandomPunctuationsFromPunctset')->invokeArgs(null, [$amount]);
    }
}
