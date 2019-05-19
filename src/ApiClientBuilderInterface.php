<?php
declare(strict_types=1);

namespace SmsFeedback;

use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;

interface ApiClientBuilderInterface
{
    /**
     * Builds API Client
     *
     * @return ApiClientInterface
     */
    public function getApiClient(): ApiClientInterface;

    /**
     * @param string $login
     * @param string $password
     *
     * @return ApiClientBuilderInterface
     */
    public function setCredentials(string $login, string $password): ApiClientBuilderInterface;

    /**
     * Sets base API url
     *
     * @param string $uri
     *
     * @return ApiClientBuilderInterface
     */
    public function setBaseUri(string $uri): ApiClientBuilderInterface;


    /**
     * Sets API response timeout
     *
     * @param int $timeout
     *
     * @return ApiClientBuilderInterface
     */
    public function setTimeout(int $timeout): ApiClientBuilderInterface;

    /**
     * Sets logger
     *
     * @param LoggerInterface $logger
     *
     * @return ApiClientBuilderInterface
     */
    public function setLogger(LoggerInterface $logger): ApiClientBuilderInterface;

    /**
     * Sets logger message template
     *
     * @param string $loggerMessageTemplate
     *
     * @return ApiClientBuilderInterface
     *@see MessageFormatter for template variables information
     */
    public function setLoggerMessageTemplate(string $loggerMessageTemplate): ApiClientBuilderInterface;

    /**
     * Sets HTTP-client directly
     *
     * This option completely overrides options set by:
     *   - setCredentials
     *   - setBaseUri
     *   - setTimeout
     *   - setLogger
     *   - setLoggerMessageTemplate
     *   - setHttpClientParams
     *
     * @param ClientInterface $httpClient
     *
     * @return ApiClientBuilderInterface
     */
    public function setHttpClient(ClientInterface $httpClient): ApiClientBuilderInterface;

    /**
     * Sets default Guzzle params
     *
     * This option completely overrides options set by:
     *   - setCredentials
     *   - setBaseUri
     *   - setTimeout
     *   - setLogger
     *   - setLoggerMessageTemplate
     *
     * @param array $params
     *
     * @return ApiClientBuilderInterface
     */
    public function setHttpClientParams(array $params): ApiClientBuilderInterface;
}
