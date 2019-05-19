SmsFeedback SDK
===============

This component allows you to simply work with smsfeedback.ru base API.

Installation
------------
```bash
composer require phpinfo/smsfeedback
``` 

Simple Usage
------------
```php
use SmsFeedback\ApiClientBuilder;

$client = ApiClientBuilder::create('login', 'password')
    ->getApiClient();

$client->send('71234567', 'Some SMS Text');

```
