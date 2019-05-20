<?php
declare(strict_types=1);

namespace SmsFeedback;

use GuzzleHttp\Exception\GuzzleException;
use SmsFeedback\Dto\BalanceDto;
use SmsFeedback\Dto\MessageDto;
use SmsFeedback\Dto\SenderDto;
use SmsFeedback\Dto\StatusDto;

interface ApiClientInterface
{
    /**
     * @param string      $phone
     * @param string      $text
     * @param string|null $sender
     * @param string|null $wapurl
     * @param string|null $scheduleTime
     * @param string|null $statusQueueName
     *
     * @return MessageDto
     * @throws GuzzleException
     */
    public function send(
        string $phone,
        string $text,
        string $sender = null,
        string $wapurl = null,
        string $scheduleTime = null,
        string $statusQueueName = null
    ): MessageDto;

    /**
     * @param string[] $ids
     *
     * @return StatusDto[]
     * @throws GuzzleException
     */
    public function status(array $ids): array;

    /**
     * @return BalanceDto[]
     * @throws GuzzleException
     */
    public function balance(): array;

    /**
     * @return SenderDto[]
     * @throws GuzzleException
     */
    public function senders(): array;
}
