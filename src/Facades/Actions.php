<?php

declare(strict_types=1);

namespace ConduitUI\Actions\Facades;

use ConduitUI\Actions\Services\ActionsService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection listWorkflowRuns(string $owner, string $repo, array $filters = [])
 * @method static \ConduitUI\Actions\Data\WorkflowRun getWorkflowRun(string $owner, string $repo, int $runId)
 * @method static bool cancelWorkflowRun(string $owner, string $repo, int $runId)
 * @method static bool rerunWorkflowRun(string $owner, string $repo, int $runId)
 * @method static bool rerunFailedJobs(string $owner, string $repo, int $runId)
 * @method static bool deleteWorkflowRun(string $owner, string $repo, int $runId)
 * @method static string getWorkflowRunLogs(string $owner, string $repo, int $runId)
 * @method static \Illuminate\Support\Collection listWorkflows(string $owner, string $repo)
 * @method static \ConduitUI\Actions\Data\Workflow getWorkflow(string $owner, string $repo, int|string $workflowId)
 * @method static bool disableWorkflow(string $owner, string $repo, int|string $workflowId)
 * @method static bool enableWorkflow(string $owner, string $repo, int|string $workflowId)
 * @method static bool createWorkflowDispatch(string $owner, string $repo, int|string $workflowId, string $ref, array $inputs = [])
 * @method static \Illuminate\Support\Collection listJobsForWorkflowRun(string $owner, string $repo, int $runId, array $filters = [])
 * @method static \ConduitUI\Actions\Data\Job getJob(string $owner, string $repo, int $jobId)
 * @method static string getJobLogs(string $owner, string $repo, int $jobId)
 * @method static bool rerunJob(string $owner, string $repo, int $jobId)
 * @method static \Illuminate\Support\Collection listArtifactsForRepository(string $owner, string $repo, array $filters = [])
 * @method static \Illuminate\Support\Collection listArtifactsForWorkflowRun(string $owner, string $repo, int $runId)
 * @method static \ConduitUI\Actions\Data\Artifact getArtifact(string $owner, string $repo, int $artifactId)
 * @method static bool deleteArtifact(string $owner, string $repo, int $artifactId)
 * @method static string downloadArtifact(string $owner, string $repo, int $artifactId)
 */
class Actions extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActionsService::class;
    }
}
