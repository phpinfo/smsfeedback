<?php
declare(strict_types=1);

namespace SmsFeedback\Factory;

use Psr\Log\LoggerInterface;
use SmsFeedback\ApiClientBuilder;
use SmsFeedback\ApiClientInterface;

abstract class ApiClientFactory
{
    public static function createApiClient(
        string $login,
        string $password,
        ?string $baseUri = null,
        ?int $timeout = null,
        ?LoggerInterface $logger = null
    ): ApiClientInterface {
        $builder = ApiClientBuilder::create($login, $password);

        if ($baseUri !== null) {
            $builder->setBaseUri($baseUri);
        }

        if ($timeout !== null) {
            $builder->setTimeout($timeout);
        }

        if ($logger !== null) {
            $builder->setLogger($logger);
        }

        return $builder->getApiClient();
    }
}
