## Radomizer

Randomizer is a php helper that helps your application to generate a random string.

Randomizer would allow you to choose the language for the randomized text (available language only).

This helper is initially purposed to generate a user's password.

## Installation

Simply add the package to your `composer.json` file and run `composer update`.

```
"ohchiko/randomizer": "0.*"
```

## Configuration

You can publish the configuration file by running:

```
php artisan vendor:publish --provider"Ohchiko\Randomizer\Providers\RandomizerServiceProvider" --tag="config"
```

When published, the `config/randomizer.php` file initially contains:

```php
return [
  'default' => [
    'language' => 'id',
  ],
];
```

## Usage

Add the `Randomizer` class to your controller or model and generate it.

```php
use Illuminate\Database\Eloquent\Model;
use Ohchiko\Randomizer\Randomizer;

class User extends Model
{
  /**
   * Get the user's randomField.
   *
   * @return string
   */
  public function getRandomFieldAttribute()
  {
    // You can use the default language
    // that is set in the config file.
    return Randomizer::generate();

    // Or you may use another available language
    return Randomizer::generate(Randomizer::LANG_EN);
  }
}
```

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) file for more information.
