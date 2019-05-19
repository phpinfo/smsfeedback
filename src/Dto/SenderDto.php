<?php
declare(strict_types=1);

namespace SmsFeedback\Dto;

class SenderDto
{
    public const STATUS_ACTIVE  = 'active';
    public const STATUS_NEW     = 'new';
    public const STATUS_PENDING = 'pending';
    public const STATUS_BLOCKED = 'blocked';
    public const STATUS_DEFAULT = 'default';

    /** @var string */
    private $name;

    /** @var string */
    private $status;

    /** @var string */
    private $comment;

    public function __construct(string $name, string $status, string $comment)
    {
        $this->name    = $name;
        $this->status  = $status;
        $this->comment = $comment;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    public function isDefault(): bool
    {
        return $this->status === self::STATUS_DEFAULT;
    }
}
