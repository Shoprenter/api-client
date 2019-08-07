# ShopRenter API test client

## Usage
```php
<?php

require_once 'vendor/autoload.php';

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

**POST request:**
```
$apiCall->execute('POST', $url, ['sku' => 'something', 'price' => 1000]);
```

**DELETE request:**
```
$apiCall->execute('DELETE', $url);
```

## Available HTTP methods
+ GET
+ POST
+ PUT
+ DELETE
