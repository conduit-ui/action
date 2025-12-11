<?php

declare(strict_types=1);

namespace ConduitUI\Action\Data;

use DateTime;

readonly class Job
{
    public function __construct(
        public int $id,
        public int $runId,
        public string $name,
        public string $status,
        public ?string $conclusion,
        public DateTime $startedAt,
        public ?DateTime $completedAt,
        public array $steps,
        public string $htmlUrl,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            runId: $data['run_id'],
            name: $data['name'],
            status: $data['status'],
            conclusion: $data['conclusion'] ?? null,
            startedAt: new DateTime($data['started_at']),
            completedAt: isset($data['completed_at']) ? new DateTime($data['completed_at']) : null,
            steps: array_map(fn ($step) => Step::fromArray($step), $data['steps'] ?? []),
            htmlUrl: $data['html_url'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'run_id' => $this->runId,
            'name' => $this->name,
            'status' => $this->status,
            'conclusion' => $this->conclusion,
            'started_at' => $this->startedAt->format('c'),
            'completed_at' => $this->completedAt?->format('c'),
            'steps' => array_map(fn (Step $step) => $step->toArray(), $this->steps),
            'html_url' => $this->htmlUrl,
        ];
    }

    public function isQueued(): bool
    {
        return $this->status === 'queued';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
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
