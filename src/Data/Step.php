<?php

declare(strict_types=1);

namespace ConduitUI\Actions\Data;

use DateTime;

readonly class Step
{
    public function __construct(
        public string $name,
        public string $status,
        public ?string $conclusion,
        public int $number,
        public DateTime $startedAt,
        public ?DateTime $completedAt,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            status: $data['status'],
            conclusion: $data['conclusion'] ?? null,
            number: $data['number'],
            startedAt: new DateTime($data['started_at']),
            completedAt: isset($data['completed_at']) ? new DateTime($data['completed_at']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'status' => $this->status,
            'conclusion' => $this->conclusion,
            'number' => $this->number,
            'started_at' => $this->startedAt->format('c'),
            'completed_at' => $this->completedAt?->format('c'),
        ];
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function wasSuccessful(): bool
    {
        return $this->conclusion === 'success';
    }

    public function wasFailed(): bool
    {
        return $this->conclusion === 'failure';
    }
}
