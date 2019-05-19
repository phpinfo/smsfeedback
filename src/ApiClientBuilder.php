<?php
declare(strict_types=1);

namespace SmsFeedback;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Log\LoggerInterface;
use SmsFeedback\Factory\DtoFactory;
use SmsFeedback\Middleware\AuthorizationMiddleware;

class ApiClientBuilder implements ApiClientBuilderInterface
{
    /**
     * Auth login
     * @var string
     */
    private $login = '';

    /**
     * Auth password
     * @var string
     */
    private $password = '';

    /**
     * @var string
     */
    private $baseUri = 'http://api.smsfeedback.ru';

    /**
     * @var int
     */
    private $timeout = 5000;

    /**
     * @var array|null
     */
    private $httpClientParams;

    /**
     * @var ClientInterface|null
     */
    private $httpClient;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @var string
     */
    private $loggerMessageTemplate = '{method} {target} HTTP/{version} {code}';

    /**
     * Creates new builder instance
     *
     * @param string $login
     * @param string $password
     *
     * @return ApiClientBuilderInterface
     */
    public static function create(string $login, string $password): ApiClientBuilderInterface
    {
        return (new self())
            ->setCredentials($login, $password);
    }

    /**
     * Builds API Client
     *
     * @return ApiClientInterface
     */
    public function getApiClient(): ApiClientInterface
    {
        $httpClient = $this->getHttpClient();
        $dtoFactory = $this->getDtoFactory();

        return new ApiClient($httpClient, $dtoFactory);
    }

    /**
     * @param string $login
     * @param string $password
     *
     * @return ApiClientBuilderInterface
     */
    public function setCredentials(string $login, string $password): ApiClientBuilderInterface
    {
        $this->login    = $login;
        $this->password = $password;

        return $this;
    }

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
    public function setHttpClientParams(array $params): ApiClientBuilderInterface
    {
        $this->httpClientParams = $params;

        return $this;
    }

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
    public function setHttpClient(ClientInterface $httpClient): ApiClientBuilderInterface
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Sets base API url
     *
     * @param string $uri
     *
     * @return ApiClientBuilderInterface
     */
    public function setBaseUri(string $uri): ApiClientBuilderInterface
    {
        $this->baseUri = $uri;

        return $this;
    }

    /**
     * Sets API response timeout
     *
     * @param int $timeout
     *
     * @return ApiClientBuilderInterface
     */
    public function setTimeout(int $timeout): ApiClientBuilderInterface
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Sets logger
     *
     * @param LoggerInterface $logger
     *
     * @return ApiClientBuilderInterface
     */
    public function setLogger(LoggerInterface $logger): ApiClientBuilderInterface
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Sets logger message template
     *
     * @param string $loggerMessageTemplate
     *
     * @return ApiClientBuilderInterface
     * @see MessageFormatter for template variables information
     */
    public function setLoggerMessageTemplate(string $loggerMessageTemplate): ApiClientBuilderInterface
    {
        $this->loggerMessageTemplate = $loggerMessageTemplate;

        return $this;
    }

    protected function getHttpClient(): ClientInterface
    {
        if ($this->httpClient !== null) {
            return $this->httpClient;
        }

        return new Client($this->getHttpClientParams());
    }

    protected function getHttpClientParams(): array
    {
        if ($this->httpClientParams !== null) {
            return $this->httpClientParams;
        }

        $params = [
            'base_uri' => $this->baseUri,
            'timeout'  => $this->timeout / 1000,
            'handler'  => $this->getHandlerStack(),
        ];

        return $params;
    }

    protected function getHandlerStack(): HandlerStack
    {
        $stack = HandlerStack::create();

        if ($this->login && $this->password) {
            $stack->push($this->getAuthorizationMiddleware());
        }

        if ($this->logger) {
            $stack->push($this->getLogMiddleware());
        }

        return $stack;
    }

    protected function getAuthorizationMiddleware(): callable
    {
        return function (callable $handler) {
            return new AuthorizationMiddleware($handler, $this->login, $this->password);
        };
    }

    protected function getLogMiddleware(): callable
    {
        return Middleware::log($this->logger, $this->getMessageFormatter());
    }

    protected function getMessageFormatter(): MessageFormatter
    {
        return new MessageFormatter($this->loggerMessageTemplate);
    }

    protected function getDtoFactory(): DtoFactory
    {
        return new DtoFactory();
    }
}
