# asanzred/ideal

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
$ composer require asanzred/ideal
```

Add ServiceProvider in your `app.php` config file.

```php
// config/app.php
'providers' => [
    ...
    Asanzred\Ideal\IdealServiceProvider::class,
]
```

## Configuration

Publish the config by running:

``` bash
    php artisan config:publish asanzred/ideal
```

## Usage

You can find an IdealController.php and routes.php with basic routes and calls

``` php
use Asanzred\Ideal\Libraries\Bs\IDeal\IDeal;

$ideal = new IDeal(Config::get('ideal.provider_url'));

// The full path to the acquirer certificate. This certificate is provided by your iDeal provider and
// must be downloaded from the merchant environment. Testing and production have different certificates.
$ideal->setAcquirerCertificate(Config::get('ideal.acquirer_cert'), true);

// Your merchant ID as specified in the merchant environment.
// Testing and production each have a different merchant ID.
$ideal->setMerchant(Config::get('ideal.merchant_id'));

// The full path to your merchant certificate.
$ideal->setMerchantCertificate(Config::get('ideal.merchant_cert'), true);

// The full path to your private key.
$ideal->setMerchantPrivateKey(Config::get('ideal.merchant_priv_key'), Config::get('ideal.merchant_priv_key_passwd'), true);

// Start a transaction request, amount in cents.
$transactionRequest = $ideal->createTransactionRequest(Config::get('ideal.merchant_issuer'), 
                                                       Config::get('ideal.merchant_return_url'), 
                                                       'purchaseId', 
                                                       1999, 
                                                       'Description');

$transactionResponse = $transactionRequest->send();

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email asanzred@gmail.com instead of using the issue tracker.

## Credits

- [Alberto Sanz Redondo][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/asanzred/ideal.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/asanzred/ideal.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/asanzred/ideal
[link-downloads]: https://packagist.org/packages/asanzred/ideal
[link-author]: https://github.com/asanzred
[link-contributors]: ../../contributors
