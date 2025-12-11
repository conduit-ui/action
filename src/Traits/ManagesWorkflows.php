<?php

declare(strict_types=1);

namespace ConduitUI\Actions\Traits;

use ConduitUI\Actions\Data\Workflow;
use Illuminate\Support\Collection;

trait ManagesWorkflows
{
    /**
     * @return \Illuminate\Support\Collection<int, \ConduitUI\Actions\Data\Workflow>
     */
    public function listWorkflows(string $owner, string $repo): Collection
    {
        $response = $this->connector->send(
            $this->connector->get("/repos/{$owner}/{$repo}/actions/workflows")
        );

        $data = $response->json();

        return collect($data['workflows'] ?? [])
            ->map(fn (array $workflow) => Workflow::fromArray($workflow));
    }

    public function getWorkflow(string $owner, string $repo, int|string $workflowId): Workflow
    {
        $response = $this->connector->send(
            $this->connector->get("/repos/{$owner}/{$repo}/actions/workflows/{$workflowId}")
        );

        return Workflow::fromArray($response->json());
    }

    public function disableWorkflow(string $owner, string $repo, int|string $workflowId): bool
    {
        $response = $this->connector->send(
            $this->connector->put("/repos/{$owner}/{$repo}/actions/workflows/{$workflowId}/disable")
        );

        return $response->successful();
    }

    public function enableWorkflow(string $owner, string $repo, int|string $workflowId): bool
    {
        $response = $this->connector->send(
            $this->connector->put("/repos/{$owner}/{$repo}/actions/workflows/{$workflowId}/enable")
        );

        return $response->successful();
    }

    public function createWorkflowDispatch(string $owner, string $repo, int|string $workflowId, string $ref, array $inputs = []): bool
    {
        $response = $this->connector->send(
            $this->connector->post("/repos/{$owner}/{$repo}/actions/workflows/{$workflowId}/dispatches", [
                'ref' => $ref,
                'inputs' => $inputs,
            ])
        );

        return $response->successful();
    }
}
