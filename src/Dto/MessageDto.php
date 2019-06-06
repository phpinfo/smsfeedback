<?php
declare(strict_types=1);

namespace SmsFeedback\Dto;

class MessageDto
{
    private const STATUS_ERROR = 'error';

    /** @var string */
    private $id;

    /** @var string */
    private $status;

    public function __construct(string $id, string $status)
    {
        $this->id     = $id;
        $this->status = $status;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isError(): bool
    {
        return $this->status === self::STATUS_ERROR;
    }
}
