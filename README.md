# ShopRenter API test client

## Usage
```php
<?php

use ShopRenter\ApiCall;

$settings = [
    'username' => '[USERNAME]',
    'api_key' => '[APIKEY]',
    'url' => '[SHOPNAME].api.shoprenter.hu',
];

$apiCall = new ApiCall($settings['username'], $settings['api_key']);

$apiCall->setFormat('json');

try {
    $response = $apiCall->execute('GET', $settings['url'] . '/products');

    echo '<pre>';
    print_r($response->getParsedResponseBody());
    echo '</pre>';
} catch (Exception $exception) {
    echo 'Something bad happened...';
}
```

## Available HTTP methods
+ GET
+ POST
+ PUT
+ DELETE
