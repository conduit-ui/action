<?php

declare(strict_types=1);

namespace ConduitUI\Actions\Data;

use DateTime;

readonly class WorkflowRun
{
    public function __construct(
        public int $id,
        public string $name,
        public int $workflowId,
        public string $status,
        public ?string $conclusion,
        public string $headBranch,
        public string $headSha,
        public string $event,
        public int $runNumber,
        public int $runAttempt,
        public DateTime $createdAt,
        public DateTime $updatedAt,
        public ?DateTime $runStartedAt,
        public string $htmlUrl,
        public ?User $actor = null,
        public ?User $triggeringActor = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            workflowId: $data['workflow_id'],
            status: $data['status'],
            conclusion: $data['conclusion'] ?? null,
            headBranch: $data['head_branch'],
            headSha: $data['head_sha'],
            event: $data['event'],
            runNumber: $data['run_number'],
            runAttempt: $data['run_attempt'],
            createdAt: new DateTime($data['created_at']),
            updatedAt: new DateTime($data['updated_at']),
            runStartedAt: isset($data['run_started_at']) ? new DateTime($data['run_started_at']) : null,
            htmlUrl: $data['html_url'],
            actor: isset($data['actor']) ? User::fromArray($data['actor']) : null,
            triggeringActor: isset($data['triggering_actor']) ? User::fromArray($data['triggering_actor']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'workflow_id' => $this->workflowId,
            'status' => $this->status,
            'conclusion' => $this->conclusion,
            'head_branch' => $this->headBranch,
            'head_sha' => $this->headSha,
            'event' => $this->event,
            'run_number' => $this->runNumber,
            'run_attempt' => $this->runAttempt,
            'created_at' => $this->createdAt->format('c'),
            'updated_at' => $this->updatedAt->format('c'),
            'run_started_at' => $this->runStartedAt?->format('c'),
            'html_url' => $this->htmlUrl,
            'actor' => $this->actor?->toArray(),
            'triggering_actor' => $this->triggeringActor?->toArray(),
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

    public function wasCancelled(): bool
    {
        return $this->conclusion === 'cancelled';
    }
}
