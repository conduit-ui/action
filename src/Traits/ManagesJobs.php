<?php

declare(strict_types=1);

namespace ConduitUI\Action\Traits;

use ConduitUI\Action\Data\Job;
use Illuminate\Support\Collection;

trait ManagesJobs
{
    /**
     * @return \Illuminate\Support\Collection<int, \ConduitUI\Action\Data\Job>
     */
    public function listJobsForWorkflowRun(string $owner, string $repo, int $runId, array $filters = []): Collection
    {
        $response = $this->connector->send(
            $this->connector->get("/repos/{$owner}/{$repo}/actions/runs/{$runId}/jobs", $filters)
        );

        $data = $response->json();

        return collect($data['jobs'] ?? [])
            ->map(fn (array $job) => Job::fromArray($job));
    }

    public function getJob(string $owner, string $repo, int $jobId): Job
    {
        $response = $this->connector->send(
            $this->connector->get("/repos/{$owner}/{$repo}/actions/jobs/{$jobId}")
        );

        return Job::fromArray($response->json());
    }

    public function getJobLogs(string $owner, string $repo, int $jobId): string
    {
        $response = $this->connector->send(
            $this->connector->get("/repos/{$owner}/{$repo}/actions/jobs/{$jobId}/logs")
        );

        return $response->body();
    }

    public function downloadJobLogs(string $owner, string $repo, int $jobId): string
    {
        return $this->getJobLogs($owner, $repo, $jobId);
    }

    public function rerunJob(string $owner, string $repo, int $jobId): bool
    {
        $response = $this->connector->send(
            $this->connector->post("/repos/{$owner}/{$repo}/actions/jobs/{$jobId}/rerun")
        );

        return $response->successful();
    }
}
