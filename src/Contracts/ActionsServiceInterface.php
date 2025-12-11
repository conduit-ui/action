<?php

declare(strict_types=1);

namespace ConduitUI\Action\Contracts;

use ConduitUI\Action\Data\Artifact;
use ConduitUI\Action\Data\Job;
use ConduitUI\Action\Data\Workflow;
use ConduitUI\Action\Data\WorkflowRun;
use Illuminate\Support\Collection;

interface ActionsServiceInterface
{
    // Workflow Runs
    public function listWorkflowRuns(string $owner, string $repo, array $filters = []): Collection;

    public function getWorkflowRun(string $owner, string $repo, int $runId): WorkflowRun;

    public function cancelWorkflowRun(string $owner, string $repo, int $runId): bool;

    public function rerunWorkflowRun(string $owner, string $repo, int $runId): bool;

    public function rerunFailedJobs(string $owner, string $repo, int $runId): bool;

    public function deleteWorkflowRun(string $owner, string $repo, int $runId): bool;

    public function getWorkflowRunLogs(string $owner, string $repo, int $runId): string;

    // Workflows
    public function listWorkflows(string $owner, string $repo): Collection;

    public function getWorkflow(string $owner, string $repo, int|string $workflowId): Workflow;

    public function disableWorkflow(string $owner, string $repo, int|string $workflowId): bool;

    public function enableWorkflow(string $owner, string $repo, int|string $workflowId): bool;

    public function createWorkflowDispatch(string $owner, string $repo, int|string $workflowId, string $ref, array $inputs = []): bool;

    // Jobs
    public function listJobsForWorkflowRun(string $owner, string $repo, int $runId, array $filters = []): Collection;

    public function getJob(string $owner, string $repo, int $jobId): Job;

    public function getJobLogs(string $owner, string $repo, int $jobId): string;

    public function rerunJob(string $owner, string $repo, int $jobId): bool;

    // Artifacts
    public function listArtifactsForRepository(string $owner, string $repo, array $filters = []): Collection;

    public function listArtifactsForWorkflowRun(string $owner, string $repo, int $runId): Collection;

    public function getArtifact(string $owner, string $repo, int $artifactId): Artifact;

    public function deleteArtifact(string $owner, string $repo, int $artifactId): bool;

    public function downloadArtifact(string $owner, string $repo, int $artifactId): string;
}
