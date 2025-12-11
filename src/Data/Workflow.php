<?php

declare(strict_types=1);

namespace ConduitUI\Action\Data;

use DateTime;

readonly class Workflow
{
    public function __construct(
        public int $id,
        public string $name,
        public string $path,
        public string $state,
        public DateTime $createdAt,
        public DateTime $updatedAt,
        public string $htmlUrl,
        public ?string $badgeUrl = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            path: $data['path'],
            state: $data['state'],
            createdAt: new DateTime($data['created_at']),
            updatedAt: new DateTime($data['updated_at']),
            htmlUrl: $data['html_url'],
            badgeUrl: $data['badge_url'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
            'state' => $this->state,
            'created_at' => $this->createdAt->format('c'),
            'updated_at' => $this->updatedAt->format('c'),
            'html_url' => $this->htmlUrl,
            'badge_url' => $this->badgeUrl,
        ];
    }

    public function isActive(): bool
    {
        return $this->state === 'active';
    }

    public function isDisabled(): bool
    {
        return $this->state === 'disabled_manually' || $this->state === 'disabled_inactivity';
    }
}
