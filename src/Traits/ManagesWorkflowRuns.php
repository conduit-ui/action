<?php

declare(strict_types=1);

namespace ConduitUI\Action\Traits;

use ConduitUI\Action\Data\WorkflowRun;
use Illuminate\Support\Collection;

trait ManagesWorkflowRuns
{
    /**
     * @return \Illuminate\Support\Collection<int, \ConduitUI\Action\Data\WorkflowRun>
     */
    public function listWorkflowRuns(string $owner, string $repo, array $filters = []): Collection
    {
        $response = $this->connector->send(
            $this->connector->get("/repos/{$owner}/{$repo}/actions/runs", $filters)
        );

        $data = $response->json();

        return collect($data['workflow_runs'] ?? [])
            ->map(fn (array $run) => WorkflowRun::fromArray($run));
    }

    public function getWorkflowRun(string $owner, string $repo, int $runId): WorkflowRun
    {
        $response = $this->connector->send(
            $this->connector->get("/repos/{$owner}/{$repo}/actions/runs/{$runId}")
        );

        return WorkflowRun::fromArray($response->json());
    }

    public function cancelWorkflowRun(string $owner, string $repo, int $runId): bool
    {
        $response = $this->connector->send(
            $this->connector->post("/repos/{$owner}/{$repo}/actions/runs/{$runId}/cancel")
        );

        return $response->successful();
    }

    public function rerunWorkflowRun(string $owner, string $repo, int $runId): bool
    {
        $response = $this->connector->send(
            $this->connector->post("/repos/{$owner}/{$repo}/actions/runs/{$runId}/rerun")
        );

        return $response->successful();
    }

    public function rerunFailedJobs(string $owner, string $repo, int $runId): bool
    {
        $response = $this->connector->send(
            $this->connector->post("/repos/{$owner}/{$repo}/actions/runs/{$runId}/rerun-failed-jobs")
        );

        return $response->successful();
    }

    public function deleteWorkflowRun(string $owner, string $repo, int $runId): bool
    {
        $response = $this->connector->send(
            $this->connector->delete("/repos/{$owner}/{$repo}/actions/runs/{$runId}")
        );

        return $response->successful();
    }

    public function getWorkflowRunLogs(string $owner, string $repo, int $runId): string
    {
        $response = $this->connector->send(
            $this->connector->get("/repos/{$owner}/{$repo}/actions/runs/{$runId}/logs")
        );

        return $response->body();
    }

    public function downloadWorkflowRunLogs(string $owner, string $repo, int $runId): string
    {
        return $this->getWorkflowRunLogs($owner, $repo, $runId);
    }
}
