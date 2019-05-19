<?php
declare(strict_types=1);

namespace SmsFeedback\Middleware;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;

class AuthorizationMiddleware
{
    /** @var callable */
    private $nextHandler;

    /**
     * Auth login
     * @var string
     */
    private $login;

    /**
     * Auth password
     * @var string
     */
    private $password;

    public function __construct(callable $nextHandler, string $login, string $password)
    {
        $this->nextHandler = $nextHandler;
        $this->login       = $login;
        $this->password    = $password;
    }

    /**
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return PromiseInterface
     */
    public function __invoke(RequestInterface $request, array $options): PromiseInterface
    {
        $fn = $this->nextHandler;

        $authHeader = sprintf('Basic %s', base64_encode($this->login . ':' . $this->password));
        $request = $request->withHeader('Authorization', $authHeader);

        return $fn($request, $options);
    }
}
