<?php
declare(strict_types=1);

namespace SmsFeedback\Factory;

use SmsFeedback\Dto\BalanceDto;
use SmsFeedback\Dto\MessageDto;
use SmsFeedback\Dto\SenderDto;
use SmsFeedback\Dto\StatusDto;

class DtoFactory
{
    /**
     * @param array $row
     *
     * @return MessageDto
     */
    public function createMessageDto(array $row): MessageDto
    {
        $id     = (string)($row[0] ?? '');
        $status = (string)($row[1] ?? '');

        return new MessageDto($id, $status);
    }

    /**
     * @param array $rows
     *
     * @return StatusDto[]
     */
    public function createStatusDtoList(array $rows): array
    {
        return array_map([$this, 'createStatusDto'], $rows);
    }

    /**
     * @param array $row
     *
     * @return StatusDto
     */
    public function createStatusDto(array $row): StatusDto
    {
        $id     = (string)($row[0] ?? '');
        $status = (string)($row[1] ?? '');

        return new StatusDto($id, $status);
    }

    /**
     * @param array $rows
     *
     * @return BalanceDto[]
     */
    public function createBalanceDtoList(array $rows): array
    {
        return array_map([$this, 'createBalanceDto'], $rows);
    }

    /**
     * @param array $row
     *
     * @return BalanceDto
     */
    public function createBalanceDto(array $row): BalanceDto
    {
        $type   = (string)($row[0] ?? '');
        $amount = (float)($row[1] ?? 0.0);
        $credit = (float)($row[2] ?? 0.0);

        return new BalanceDto($type, $amount, $credit);
    }

    /**
     * @param array $rows
     *
     * @return SenderDto[]
     */
    public function createSenderDtoList(array $rows): array
    {
        return array_map([$this, 'createSenderDto'], $rows);
    }

    /**
     * @param array $row
     *
     * @return SenderDto
     */
    public function createSenderDto(array $row): SenderDto
    {
        $name    = (string)($row[0] ?? '');
        $status  = (string)($row[1] ?? '');
        $comment = (string)($row[2] ?? '');

        return new SenderDto($name, $status, $comment);
    }
}
