<?php

declare(strict_types=1);

namespace ConduitUI\Actions\Data;

use DateTime;

readonly class Artifact
{
    public function __construct(
        public int $id,
        public string $name,
        public int $sizeInBytes,
        public string $url,
        public string $archiveDownloadUrl,
        public bool $expired,
        public DateTime $createdAt,
        public DateTime $updatedAt,
        public ?DateTime $expiresAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            sizeInBytes: $data['size_in_bytes'],
            url: $data['url'],
            archiveDownloadUrl: $data['archive_download_url'],
            expired: $data['expired'],
            createdAt: new DateTime($data['created_at']),
            updatedAt: new DateTime($data['updated_at']),
            expiresAt: isset($data['expires_at']) ? new DateTime($data['expires_at']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'size_in_bytes' => $this->sizeInBytes,
            'url' => $this->url,
            'archive_download_url' => $this->archiveDownloadUrl,
            'expired' => $this->expired,
            'created_at' => $this->createdAt->format('c'),
            'updated_at' => $this->updatedAt->format('c'),
            'expires_at' => $this->expiresAt?->format('c'),
        ];
    }

    public function isExpired(): bool
    {
        return $this->expired;
    }

    public function sizeInMb(): float
    {
        return round($this->sizeInBytes / 1024 / 1024, 2);
    }
}
