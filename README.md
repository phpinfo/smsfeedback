SmsFeedback SDK
===============
This component allows you to simply work with [smsfeedback.ru](https://smsfeedback.ru) base API.

Installation
------------
```bash
composer require phpinfo/smsfeedback
``` 

Simple Usage
------------
The best way to instantiate API client is to use *ApiClientBuilder*:
```php
$client = ApiClientBuilder::create('login', 'password')
    ->getApiClient();

$client->send('71234567', 'Some SMS Text');
```

Basic Configuration
-------------------
You can specify connection timeout (in msec) or API base URI:
```php
$client = ApiClientBuilder::create('login', 'password')
    ->setTimeout(3000)
    ->setBaseUri('https://service.mock')
    ->getApiClient();
```

Logging requests
----------------
SDK builder supports [psr/logger](https://github.com/php-fig/log). [Monolog](https://github.com/Seldaek/monolog) 
usage example:

```php
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$handler = new StreamHandler(STDOUT);
$logger  = new Logger('SmsFeedback', [$handler]);
        
$client = ApiClientBuilder::create('login', 'password')
    ->setLogger($logger)
    ->getApiClient();
    
$client->balance();
```

Will output:
```bash
[2019-05-19 20:21:34] SmsFeedback.INFO: GET /messages/v2/balance HTTP/1.1 200 [] []
```

You can specify logger message template. Default value is: "{method} {target} HTTP/{version} {code}"
```php
$client = ApiClientBuilder::create('login', 'password')
    ->setLogger($logger)
    ->setLoggerMessageTemplate('my template')
    ->getApiClient();
```

See Guzzle [MessageFormatter](https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php) template variables for more information.  

Sending SMS
-----------

Retrieving SMS status
--------------------

Retrieving balance
------------------

Retrieving senders
------------------

Manual control
--------------
SDK uses [Guzzle 6](https://github.com/guzzle/guzzle) under the hood. You can specify Guzzle params directly:
```php
$client = ApiClientBuilder::create('login', 'password')
    ->setHttpClientParams(
        [
            'base_uri' => 'https://my-domain.com',
            'timeout'  => 5.2,
        ]
    )
    ->getApiClient();
```

Note: Guzzle params will completely override timeout, base URI, logger and authorization features.
You have to specify everything manually. In some cases it might be useful.

AuthorizationMiddleware
-----------------------
```php
$stack = HandlerStack::create();

$stack->push(function (callable $handler) use ($login, $password) {
    return new AuthorizationMiddleware($handler, $login, $password);
});

$client = ApiClientBuilder::create()
    ->setHttpClientParams(
        [
            'base_uri' => 'http://api.smsfeedback.ru',
            'timeout'  => 5.0,
            'handler'  => $stack,
        ]
    )
    ->getApiClient();
```

Custom authorization middleware
-------------------------------
```php
$stack = HandlerStack::create();

$stack->push(function (callable $handler) use ($login, $password) {
    return function (RequestInterface $request, array $options) use ($handler, $login, $password) {
        $fn = $handler;

        $authHeader = sprintf('Basic %s', base64_encode($login . ':' . $password));
        $request = $request->withHeader('Authorization', $authHeader);

        return $fn($request, $options);
    };
});

$client = ApiClientBuilder::create()
    ->setHttpClientParams(
        [
            'base_uri' => 'http://api.smsfeedback.ru',
            'timeout'  => 5.0,
            'handler'  => $stack,
        ]
    )
    ->getApiClient();
```

Logger middleware
-----------------
```php
$stack = HandlerStack::create();

$logger    = new Logger('SmsFeedback', [$handler]);
$formatter = new MessageFormatter(MessageFormatter::SHORT);

$stack->push(Middleware::log($logger, $formatter));

$client = ApiClientBuilder::create()
    ->setHttpClientParams(
        [
            'base_uri' => 'http://api.smsfeedback.ru',
            'timeout'  => 5.0,
            'handler'  => $stack,
        ]
    )
    ->getApiClient();
```
