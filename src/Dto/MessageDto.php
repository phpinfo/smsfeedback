<?php
declare(strict_types=1);

namespace SmsFeedback\Dto;

class MessageDto
{
    public const STATUS_ACCEPTED                     = 'accepted';
    public const STATUS_INVALID_MOBILE_PHONE         = 'invalid mobile phone';
    public const STATUS_ERROR_AUTHORIZATION          = 'error authorization';
    public const STATUS_TEXT_IS_EMPTY                = 'text is empty';
    public const STATUS_TEXT_MUST_BE_STRING          = 'text must be string';
    public const STATUS_SENDER_ADDRESS_INVALID       = 'sender address invalid';
    public const STATUS_WAPURL_INVALID               = 'wapurl invalid';
    public const STATUS_INVALID_SCHEDULE_TIME_FORMAT = 'invalid schedule time format';
    public const STATUS_INVALID_STATUS_QUEUE_NAME    = 'invalid status queue name';
    public const STATUS_NOT_ENOUGH_BALANCE           = 'not enough balance';

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
}
