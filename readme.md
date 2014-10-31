# MoIP (Laravel Package)
----------------------

[![Latest Stable Version](https://poser.pugx.org/SOSTheBlack/moip/v/stable.svg)](https://packagist.org/packages/SOSTheBlack/moip) [![Total Downloads](https://poser.pugx.org/SOSTheBlack/moip/downloads.svg)](https://packagist.org/packages/SOSTheBlack/moip) [![Latest Unstable Version](https://poser.pugx.org/SOSTheBlack/moip/v/unstable.svg)](https://packagist.org/packages/SOSTheBlack/moip) [![License](https://poser.pugx.org/SOSTheBlack/moip/license.svg)](https://packagist.org/packages/SOSTheBlack/moip)

This is a package of a payment intermediary called MOIP.

### Required setup

In the `require` key of `composer.json` file add the following

    "SOSTheBlack/moip": "dev-master"

Run the Composer update comand

    $ composer update

In your `config/app.php` add `'SOSTheBlack\Moip\ServiceProvider'` to the end of the `$providers` array

    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'SOSTheBlack\Moip\ServiceProvider',

    ),

Then at the end of `config/app.php` add `'Moip'    => 'SOSTheBlack\Moip\Facade'` to the `$aliases` array

    'aliases' => array(

        'App'        => 'Illuminate\Support\Facades\App',
        'Artisan'    => 'Illuminate\Support\Facades\Artisan',
        ...
        'Moip'       => 'SOSTheBlack\Moip\Facade',

    ),

Copy the configuration file config/moip.php to app/config / folder. Law making that file changes it deems necessary.

### sending data for create chackout page

Now you are ready to go:

    // Riding array
    $data = [
        'unique_id' => $id_buy,
        'reason'    => 'Promotion of rule markup 01',
        'values'    => ['value' => $full_price_products , 'adds' => $freight, 'deduct'=> $discount],
        'receiver'  => 'jeancesargarcia@gmail.com'
    ];

    // Creating the checkout page
    try {
        $moip = Moip::sendMoip($data);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

### Data Response

    // Error: false or error message
    $moip->error;

    // Response: true or false
    $moip->response;

    // token request
    $moip->token;

    // payment url
    $moip->payment_url;

    // xml sent
    $moip->xmlSend;

    // xml reponse