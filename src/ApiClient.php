<?php
declare(strict_types=1);

namespace SmsFeedback;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use function GuzzleHttp\Psr7\build_query;
use function GuzzleHttp\Psr7\readline;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use SmsFeedback\Dto\BalanceDto;
use SmsFeedback\Dto\MessageDto;
use SmsFeedback\Dto\SenderDto;
use SmsFeedback\Dto\StatusDto;
use SmsFeedback\Factory\DtoFactory;

class ApiClient implements ApiClientInterface
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var DtoFactory
     */
    private $dtoFactory;

    public function __construct(ClientInterface $httpClient, DtoFactory $dtoFactory)
    {
        $this->httpClient = $httpClient;
        $this->dtoFactory = $dtoFactory;
    }

    /**
     * @param string      $phone
     * @param string      $text
     * @param string|null $sender
     * @param string|null $statusQueueName
     * @param string|null $scheduleTime
     *
     * @return MessageDto
     * @throws GuzzleException
     */
    public function send(
        string $phone,
        string $text,
        ?string $sender = null,
        ?string $statusQueueName = null,
        ?string $scheduleTime = null
    ): MessageDto {
        $params = [
            'phone'           => $phone,
            'text'            => $text,
            'sender'          => $sender,
            'statusQueueName' => $statusQueueName,
            'scheduleTime'    => $scheduleTime,
        ];

        $rows = $this->request('messages/v2/send', $params);
        $row  = reset($rows) ?: [];

        return $this->dtoFactory->createMessageDto($row);
    }

    /**
     * @param string[] $ids
     *
     * @return StatusDto[]
     * @throws GuzzleException
     */
    public function status(array $ids): array
    {
        $q    = '?id=' . implode('&id=', $ids);
        $rows = $this->request('messages/v2/status' . $q);

        return $this->dtoFactory->createStatusDtoList($rows);
    }

    /**
     * @return BalanceDto[]
     * @throws GuzzleException
     */
    public function balance(): array
    {
        $rows = $this->request('messages/v2/balance');

        return $this->dtoFactory->createBalanceDtoList($rows);
    }

    /**
     * @return SenderDto[]
     * @throws GuzzleException
     */
    public function senders(): array
    {
        $rows = $this->request('messages/v2/senders.json');

        return $this->dtoFactory->createSenderDtoList($rows);
    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @return array
     * @throws GuzzleException
     */
    protected function request(string $url, array $params = []): array
    {
        $request  = $this->createRequest($url, $params);
        $response = $this->sendRequest($request);

        return $this->denormalizeResponseSteam($response->getBody());
    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @return RequestInterface
     */
    protected function createRequest(string $url, array $params = []): RequestInterface
    {
        if ($params) {
            $url .= '?' . build_query($params);
        }

        return new Request('GET', $url);
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->send($request);
    }

    /**
     * @param StreamInterface $stream
     *
     * @return array
     */
    protected function denormalizeResponseSteam(StreamInterface $stream): array
    {
        $rows = [];

        while ($line = readline($stream)) {
            $rows[] = explode(';', $line);
        }

        return $rows;
    }
}
