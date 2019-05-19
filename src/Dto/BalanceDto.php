<?php
declare(strict_types=1);

namespace SmsFeedback\Dto;

class BalanceDto
{
    /** @var string */
    private $type;

    /** @var float */
    private $amount;

    /** @var float */
    private $credit;

    public function __construct(string $type, float $amount, float $credit)
    {
        $this->type   = $type;
        $this->amount = $amount;
        $this->credit = $credit;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCredit(): float
    {
        return $this->credit;
    }
}
