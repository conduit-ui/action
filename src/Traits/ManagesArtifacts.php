<?php

declare(strict_types=1);

namespace ConduitUI\Actions\Traits;

use ConduitUI\Actions\Data\Artifact;
use Illuminate\Support\Collection;

trait ManagesArtifacts
{
    /**
     * @return \Illuminate\Support\Collection<int, \ConduitUI\Actions\Data\Artifact>
     */
    public function listArtifactsForRepository(string $owner, string $repo, array $filters = []): Collection
    {
        $response = $this->connector->send(
            $this->connector->get("/repos/{$owner}/{$repo}/actions/artifacts", $filters)
        );

        $data = $response->json();

        return collect($data['artifacts'] ?? [])
            ->map(fn (array $artifact) => Artifact::fromArray($artifact));
    }

    /**
     * @return \Illuminate\Support\Collection<int, \ConduitUI\Actions\Data\Artifact>
     */
    public function listArtifactsForWorkflowRun(string $owner, string $repo, int $runId): Collection
    {
        $response = $this->connector->send(
            $this->connector->get("/repos/{$owner}/{$repo}/actions/runs/{$runId}/artifacts")
        );

        $data = $response->json();

        return collect($data['artifacts'] ?? [])
            ->map(fn (array $artifact) => Artifact::fromArray($artifact));
    }

    public function getArtifact(string $owner, string $repo, int $artifactId): Artifact
    {
        $response = $this->connector->send(
            $this->connector->get("/repos/{$owner}/{$repo}/actions/artifacts/{$artifactId}")
        );

        return Artifact::fromArray($response->json());
    }

    public function deleteArtifact(string $owner, string $repo, int $artifactId): bool
    {
        $response = $this->connector->send(
            $this->connector->delete("/repos/{$owner}/{$repo}/actions/artifacts/{$artifactId}")
        );

        return $response->successful();
    }

    public function downloadArtifact(string $owner, string $repo, int $artifactId): string
    {
        $response = $this->connector->send(
            $this->connector->get("/repos/{$owner}/{$repo}/actions/artifacts/{$artifactId}/zip")
        );

        return $response->body();
    }
}
