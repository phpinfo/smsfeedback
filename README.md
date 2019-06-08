SmsFeedback SDK
===============
This component allows you to simply work with [smsfeedback.ru](https://smsfeedback.ru) base API.

See service [API documentation](https://www.smsfeedback.ru/smsapi/) for more detailed information.

Installation
------------
```bash
composer require phpinfo/smsfeedback
``` 

Simple Usage
------------
The best way to instantiate API client is to use *ApiClientBuilder*:
```php
$client = ApiClientFactory::createApiClient('login', 'password');

$client->send('71234567', 'Some SMS Text');
```
You can specify connection timeout (in msec) or API base URI:
```php
$client = ApiClientFactory::createApiClient('login', 'password', 'https://service.mock', 3000);
```

Logging Requests
----------------
SDK builder supports [psr/logger](https://github.com/php-fig/log). [Monolog](https://github.com/Seldaek/monolog) 
usage example:

```php
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$handler = new StreamHandler(STDOUT);
$logger  = new Logger('SmsFeedback', [$handler]);
        
$client = ApiClientFactory::createApiClient('login', 'password', null, null, $logger);
$client->balance();
```

Will output:
```bash
[2019-05-19 20:21:34] SmsFeedback.INFO: GET /messages/v2/balance HTTP/1.1 200 [] []
```  

Symfony 4
---------
See [SmsFeedback Symfony Bundle](https://github.com/phpinfo/smsfeedback-bundle) for easy integration.

You can use `ApiClientFactory` in your DI container as well:

```yaml
SmsFeedback\ApiClientInterface:
    factory: ['SmsFeedback\Factory\ApiClientFactory', 'createApiClient']
    arguments:
        $login: '%env(SMSFEEDBACK_LOGIN)%'
        $password: '%env(SMSFEEDBACK_PASSWORD)%'
```

Sending SMS
-----------
Simply sends SMS:
```php
$message = $client->send('79161234567', 'SMS Text');
```

| Argument        | Type    | Description                               | Example                   |
|-----------------|---------|-------------------------------------------|---------------------------|
| **phone**       | string  | Phone number                              | 79161234567               |
| **text**        | string  | SMS Text                                  | Some SMS Text             |
| sender          | ?string | Sender short name                         | SENDER                    |
| statusQueueName | ?string | SMS status queue name                     | my-queue                  |
| scheduleTime    | ?string | Scheduled time to send message (UTC only) | 2009-01-01T12:30:01+00:00 |

Response object:
```json
{
    "id": "A133541BC",
    "status": "accepted"
}
```

Retrieving SMS status
--------------------
```php
$statuses = $client->status(['5169837636', '5169837647']);
```
```json
[
    {
        "id": "5169837636",
        "status": "delivered"
    },
    {
        "id": "5169837647",
        "status": "delivery error"
    }
]
```

Retrieving balance
------------------
```php
$balances = $client->balance();
```
```json
[
    {
        "type": "RUB",
        "amount": 385.5,
        "credit": 0.0
    }
]
```

Retrieving senders
------------------
```php
$senders = $client->senders();
```
```json
[
    {
        "name": "SENDER",
        "status": "active",
        "comment": "Some sender comment"
    }
]
```

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
