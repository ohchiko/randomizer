# Randomizer

Randomizer is a php helper that helps your application to generate a random string.

This package is initially comes from an idea to generate a user's password.

## Installation

Simply add the package to your `composer.json` file and run `composer update`.

```
"ohchiko/randomizer": "^0.1.0"
```

## Usage

To generate a random string, simply add below line to your code.

```php
$random = Randomizer::generate();
```

It will return a string containing random words concatenated with random punctutations. Below is the example result.

```php
string(21) "kerikilLajuLiontin*!%"    // Using default language (id)
```

If you want to change the amount of words and/or punctuations in the result string, you may supply the amount in the method.

```php
Randomizer::generate(1, 3);    // returns something like: string(11) "sekarang(@!"
Randomizer::generate(3, 1);    // returns something like: string(11) "batuPesawatSekolah&"
```

### How It Works

Randomizer is generating a string which contains random words and random punctuations.

The words and punctuations are taken from a set. There are `wordset` and `punctset` which stores a list of words and punctuations.

By default, `wordset` are taken from one of [wordset files](resources/wordsets) which identified by the language used. And `punctset` is taken from  an array of punctuations.

For example, the current available languages are: `id` and `en`, and the default language used is `id`. The wordset file for those languages are: `id_wordset` and `en_wordset`. So, when `generate` method called, it will take a random word(s) from `id_wordset` file because the language used is `id`.

The amount of words and punctuations to be taken is identified by the `generate` method. The default value for both words and punctuations amount is `3`. See [Available Methods section](#available-methods) for detail.

You can change the language by calling the `setLanguage` method before the `generate` method. The argument supplied can be the language constants (which currently are `LANG_ID` and `LANG_EN`) or the language code (ex. `en`).

```php
Randomizer::setLanguage(Randomizer::LANG_EN);    // or Randomizer::setLanguge('en');
Randomizer::generate();
```

If you prefered using your own wordset and/or punctset, you can tell Randomizer to use that instead by calling the `setCustomWordset` and/or `setCustomPunctset` method.
```php
Randomizer::setCustomWordset(["english", "okay", "airplane", "sky", "eyeglasses", "noise"]);
Randomizer::setCustomPunctset([";", ">", "=", "?"]);
```

After that, you can check the customized wordset and punctset by calling `getCustomWordset` and/or `getCustomPunctset` method.

### Available Methods

Besides the main method `generate`, there also a number of other methods available.

```php
Randomizer::generate(int $wordCount = 3, int $punctCount = 3): string   // Generate a string containing random words and punctuations.
Randomizer::setLanguage(string $language): void                         // Sets the wordset language to randomized. Only supported languages.
Randomizer::getLanguage(): array                                        // Gets the current wordset language.
Randomizer::getAvailableLanguages(): array                              // Gets the current supported languages.
Randomizer::setCustomWordset(array $wordset): void                      // Sets a custom wordset to be randomized.
Randomizer::getCustomWordset(): ?array                                  // Gets a list of words of the current customized wordset.
Randomizer::getDefaultWordset(): array                                  // Gets a list of words of the default wordset. Warning: this method may returns a huge number of array members.
Randomizer::setCustomPunctset(array $punctset): void                    // Sets a custom punctset to be randomized.
Randomizer::getCustomPunctset(): ?array                                 // Gets a list of current customized punctset. Returns null if none.
Randomizer::getDefaultPunctset(): array                                 // Gets a list of punctuations of the default punctset.
```

### Available Language Constants

```php
Randomizer::LANG_ID
Randomizer::LANG_EN
```

The language constants are hardcoded, so in case to add a new wordset language, I need to update the code to add that language constant.

### Testing

You can use [phpunit](https://phpunit.de/) from the package root to run the test file.

```sh
vendor/bin/phpunit tests/
```

## Support

If you ever encountered any issues while using this package, you may create a new issue report. If you have any idea on supporting this package (it may be a new wordsets, bug fixes, new feature, etc.), you may create a pull request and I will review it before it can be merged.

Please remember that this package is comes from an idea of creating a random user's password, so this package may not lead to what it is should be. This package soon may be a real *randomizer*, so please support me. :)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) file for more information.

## Credits

- [dbrw](https://github.com/dbrw): Full support on this package
- [sastrawi/sastrawi](https://github.com/sastrawi/sastrawi): Indonesian word list
- [paritytech/wordlist](https://github.com/paritytech/wordlist): English word list
