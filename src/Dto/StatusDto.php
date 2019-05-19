<?php
declare(strict_types=1);

namespace SmsFeedback\Dto;

class StatusDto
{
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
