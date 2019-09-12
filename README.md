## Randomizer

Randomizer is a php helper that helps your application to generate a random string.

Randomizer would allow you to choose the language for the randomized text (available language only).

This helper is initially purposed to generate a user's password.

## Installation

Simply add the package to your `composer.json` file and run `composer update`.

```
"ohchiko/randomizer": "<0.1.*"
```

## Usage

Below is an example of using `Randomizer` inside a model.

```php
use Illuminate\Database\Eloquent\Model;
use Ohchiko\Randomizer\Randomizer;

class User extends Model
{
    // ...

    public function getRandomKeyAttribute()
    {
        // Using default language (which is `LANG_ID`: Indonesian)
        return Randomizer::generate();

        // Or using custom language that available
        return Randomizer::generate(Randomizer::LANG_EN);
    }
}
```

#### Example result

```php
User {
    ...,
    randomKey: "relatifEkoklimatologiKatalisit^",
    ...
}
```

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) file for more information.

## Credits

- [sastrawi/sastrawi](https://github.com/sastrawi/sastrawi): Indonesian word list
- [paritytech/wordlist](https://github.com/paritytech/wordlist): English word list
