# Action

[![Latest Version on Packagist](https://img.shields.io/packagist/v/conduit-ui/action.svg?style=flat-square)](https://packagist.org/packages/conduit-ui/action)
[![Total Downloads](https://img.shields.io/packagist/dt/conduit-ui/action.svg?style=flat-square)](https://packagist.org/packages/conduit-ui/action)
[![Tests](https://img.shields.io/github/actions/workflow/status/conduit-ui/action/tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/conduit-ui/action/actions)

**Your GitHub Actions bill is too high.** Delete stale artifacts, monitor failing workflows, and automate runner management from PHP.

Built for teams tired of manually cleaning up artifacts and babysitting CI/CD pipelines.

## The Problem

You're burning money on:
- Artifacts nobody downloaded (storage costs add up fast)
- Failed workflow runs you forgot to rerun
- Workflows running on the wrong branch
- Manual artifact downloads for debugging
- Workflow runs you can't easily track

GitHub charges $0.25/GB for artifact storage. A single forgotten artifact can cost hundreds per month.

## Installation

```bash
composer require conduit-ui/action
```

## Usage

### Stop Wasting Money on Artifacts

```php
use ConduitUI\Action\Facades\Actions;

// Find artifacts older than 30 days
$artifacts = Actions::listArtifactsForRepository('your-org/your-repo');

$stale = $artifacts->filter(function ($artifact) {
    return $artifact->createdAt->diffInDays(now()) > 30;
});

// Delete them (save $$$ immediately)
foreach ($stale as $artifact) {
    Actions::deleteArtifact('your-org/your-repo', $artifact->id);
    echo "Deleted {$artifact->name} ({$artifact->sizeInMb()} MB)\n";
}

// Savings: $0.25/GB × cleaned storage
```

### Auto-Retry Failed Workflows

```php
// Get failed runs from today
$runs = Actions::listWorkflowRuns('your-org/your-repo', [
    'status' => 'completed',
    'conclusion' => 'failure',
    'created' => '>=' . now()->startOfDay()->toIso8601String(),
]);

// Retry only the failed jobs
foreach ($runs as $run) {
    Actions::rerunFailedJobs('your-org/your-repo', $run->id);
    echo "Retrying failed jobs in run #{$run->runNumber}\n";
}
```

### Download Artifacts for Debugging

```php
// Get the latest build artifact
$artifacts = Actions::listArtifactsForWorkflowRun(
    'your-org/your-repo',
    $runId
);

$buildArtifact = $artifacts->firstWhere('name', 'build-output');

if ($buildArtifact && !$buildArtifact->isExpired()) {
    $zipContent = Actions::downloadArtifact(
        'your-org/your-repo',
        $buildArtifact->id
    );

    file_put_contents('/tmp/build.zip', $zipContent);
}
```

### Monitor Workflow Health

```php
// Check if CI is healthy
$runs = Actions::listWorkflowRuns('your-org/your-repo', [
    'workflow_id' => 'ci.yml',
    'branch' => 'main',
]);

$recent = $runs->take(10);
$failureRate = $recent->filter(fn($r) => $r->wasFailed())->count() / $recent->count();

if ($failureRate > 0.3) {
    // Alert: CI is failing 30%+ of the time
    notify("CI health degraded: {$failureRate}% failure rate");
}
```

### Workflow Automation

```php
// Trigger a deployment
Actions::createWorkflowDispatch(
    'your-org/your-repo',
    'deploy.yml',
    'main',
    [
        'environment' => 'production',
        'version' => 'v2.4.1',
    ]
);

// Disable a problematic workflow
Actions::disableWorkflow('your-org/your-repo', 'broken.yml');

// Enable it after fixing
Actions::enableWorkflow('your-org/your-repo', 'broken.yml');
```

### Cost Analysis Dashboard

```php
// Calculate artifact storage costs
$artifacts = Actions::listArtifactsForRepository('your-org/your-repo');

$stats = [
    'total_artifacts' => $artifacts->count(),
    'total_size_gb' => $artifacts->sum(fn($a) => $a->sizeInMb()) / 1024,
    'expired' => $artifacts->filter(fn($a) => $a->isExpired())->count(),
    'monthly_cost' => ($artifacts->sum(fn($a) => $a->sizeInMb()) / 1024) * 0.25,
];

// Breakdown by workflow
$byWorkflow = $artifacts->groupBy('workflowRun.name')->map(function ($items, $name) {
    return [
        'count' => $items->count(),
        'size_mb' => $items->sum(fn($a) => $a->sizeInMb()),
        'cost' => ($items->sum(fn($a) => $a->sizeInMb()) / 1024) * 0.25,
    ];
});
```

## API Reference

### Workflows

```php
Actions::listWorkflows('owner', 'repo')
Actions::getWorkflow('owner', 'repo', 'ci.yml')
Actions::disableWorkflow('owner', 'repo', 'ci.yml')
Actions::enableWorkflow('owner', 'repo', 'ci.yml')
Actions::createWorkflowDispatch('owner', 'repo', 'deploy.yml', 'main', ['key' => 'value'])
```

### Workflow Runs

```php
Actions::listWorkflowRuns('owner', 'repo', [
    'status' => 'completed',        // queued, in_progress, completed
    'conclusion' => 'success',      // success, failure, cancelled
    'branch' => 'main',
    'event' => 'push',              // push, pull_request, workflow_dispatch
])
Actions::getWorkflowRun('owner', 'repo', $runId)
Actions::cancelWorkflowRun('owner', 'repo', $runId)
Actions::rerunWorkflowRun('owner', 'repo', $runId)
Actions::rerunFailedJobs('owner', 'repo', $runId)
Actions::deleteWorkflowRun('owner', 'repo', $runId)
```

### Jobs

```php
Actions::listJobsForWorkflowRun('owner', 'repo', $runId)
Actions::getJob('owner', 'repo', $jobId)
Actions::getJobLogs('owner', 'repo', $jobId)
Actions::rerunJob('owner', 'repo', $jobId)
```

### Artifacts

```php
Actions::listArtifactsForRepository('owner', 'repo')
Actions::listArtifactsForWorkflowRun('owner', 'repo', $runId)
Actions::getArtifact('owner', 'repo', $artifactId)
Actions::downloadArtifact('owner', 'repo', $artifactId)  // Returns ZIP content
Actions::deleteArtifact('owner', 'repo', $artifactId)
```

## DTOs

Clean, typed objects for everything:

```php
// WorkflowRun
$run->id                    // int
$run->name                  // string
$run->status                // queued|in_progress|completed
$run->conclusion            // success|failure|cancelled|null
$run->headBranch            // string
$run->event                 // push|pull_request|etc
$run->runNumber             // int
$run->htmlUrl               // string
$run->isCompleted()         // bool
$run->wasSuccessful()       // bool
$run->wasFailed()           // bool

// Workflow
$workflow->id               // int
$workflow->name             // string
$workflow->path             // string (.github/workflows/ci.yml)
$workflow->state            // active|disabled
$workflow->isActive()       // bool

// Artifact
$artifact->id               // int
$artifact->name             // string
$artifact->sizeInBytes      // int
$artifact->sizeInMb()       // float
$artifact->expired          // bool
$artifact->isExpired()      // bool
$artifact->createdAt        // DateTime
$artifact->expiresAt        // DateTime|null

// Job
$job->id                    // int
$job->name                  // string
$job->status                // queued|in_progress|completed
$job->conclusion            // success|failure|cancelled|null
$job->steps                 // array<Step>
$job->isCompleted()         // bool
$job->wasSuccessful()       // bool
```

## Real-World Examples

### Automated Artifact Cleanup (Cron Job)

```php
// Run daily: php artisan schedule:run
Schedule::call(function () {
    $repos = ['your-org/api', 'your-org/frontend', 'your-org/mobile'];

    foreach ($repos as $repo) {
        $artifacts = Actions::listArtifactsForRepository($repo);

        // Delete artifacts older than 7 days
        $artifacts->filter(fn($a) => $a->createdAt->diffInDays() > 7)
            ->each(fn($a) => Actions::deleteArtifact($repo, $a->id));
    }
})->daily();
```

### CI Health Monitoring

```php
// Monitor all workflows
$workflows = Actions::listWorkflows('your-org/your-repo');

foreach ($workflows as $workflow) {
    if (!$workflow->isActive()) continue;

    $runs = Actions::listWorkflowRuns('your-org/your-repo', [
        'workflow_id' => $workflow->id,
    ]);

    $recentRuns = $runs->take(20);
    $failures = $recentRuns->filter(fn($r) => $r->wasFailed())->count();

    if ($failures > 10) {
        alert("Workflow {$workflow->name} is failing frequently");
    }
}
```

### Smart Retry Logic

```php
// Only retry transient failures
$run = Actions::getWorkflowRun('your-org/your-repo', $runId);

if ($run->wasFailed()) {
    $jobs = Actions::listJobsForWorkflowRun('your-org/your-repo', $runId);

    // Check if failures look transient (network, timeout)
    $transient = $jobs->filter(function ($job) {
        return $job->wasFailed() &&
               str_contains($job->conclusion, 'timeout') ||
               str_contains($job->conclusion, 'network');
    });

    if ($transient->isNotEmpty()) {
        Actions::rerunFailedJobs('your-org/your-repo', $runId);
    }
}
```

## Framework Agnostic

Works with any PHP framework:

```php
// Laravel
use ConduitUI\Action\Facades\Actions;

// Standalone
use ConduitUI\Action\Services\ActionsService;
use ConduitUI\Connector\GitHub;

$github = new GitHub(token: $_ENV['GITHUB_TOKEN']);
$actions = new ActionsService($github);
```

## Related Packages

Part of the [Conduit UI](https://github.com/conduit-ui) ecosystem:

- [conduit-ui/commit](https://github.com/conduit-ui/commit) - Commit history analysis
- [conduit-ui/pr](https://github.com/conduit-ui/pr) - Pull request automation
- [conduit-ui/issue](https://github.com/conduit-ui/issue) - Issue management
- [conduit-ui/connector](https://github.com/conduit-ui/connector) - GitHub API client

## Enterprise Support

Managing CI/CD at scale across dozens of repos? We provide:
- Custom workflow automation
- Cost optimization audits
- Dedicated support with SLA

Contact: [Conduit UI](https://github.com/conduit-ui)

## Testing

```bash
composer test
composer analyse
composer format
```

## License

MIT License - see [LICENSE](LICENSE.md)
