# GitHub Actions for Conduit UI

A clean, expressive Laravel package for managing GitHub Actions workflows, runs, jobs, and artifacts.

Built on [`conduit-ui/github-connector`](https://github.com/conduit-ui/connector) for seamless GitHub API integration.

## Installation

```bash
composer require conduit-ui/github-actions
```

Publish the config file (optional):

```bash
php artisan vendor:publish --tag="github-actions-config"
```

## Usage

### Workflow Runs

```php
use ConduitUI\Actions\Facades\Actions;

// List all workflow runs
$runs = Actions::listWorkflowRuns('owner', 'repo');

// Filter workflow runs
$runs = Actions::listWorkflowRuns('owner', 'repo', [
    'status' => 'failure',
    'branch' => 'main',
    'event' => 'push',
]);

// Get a specific run
$run = Actions::getWorkflowRun('owner', 'repo', 123);

// Cancel a run
Actions::cancelWorkflowRun('owner', 'repo', 123);

// Rerun a workflow
Actions::rerunWorkflowRun('owner', 'repo', 123);

// Rerun only failed jobs
Actions::rerunFailedJobs('owner', 'repo', 123);

// Get workflow run logs
$logs = Actions::getWorkflowRunLogs('owner', 'repo', 123);

// Delete a workflow run
Actions::deleteWorkflowRun('owner', 'repo', 123);
```

### Workflows

```php
// List all workflows
$workflows = Actions::listWorkflows('owner', 'repo');

// Get a workflow by ID or filename
$workflow = Actions::getWorkflow('owner', 'repo', 'ci.yml');
$workflow = Actions::getWorkflow('owner', 'repo', 123);

// Disable a workflow
Actions::disableWorkflow('owner', 'repo', 'ci.yml');

// Enable a workflow
Actions::enableWorkflow('owner', 'repo', 'ci.yml');

// Trigger workflow_dispatch
Actions::createWorkflowDispatch('owner', 'repo', 'deploy.yml', 'main', [
    'environment' => 'production',
    'version' => '1.0.0',
]);
```

### Jobs

```php
// List jobs for a workflow run
$jobs = Actions::listJobsForWorkflowRun('owner', 'repo', 123);

// Filter jobs
$jobs = Actions::listJobsForWorkflowRun('owner', 'repo', 123, [
    'filter' => 'latest',
]);

// Get a specific job
$job = Actions::getJob('owner', 'repo', 456);

// Get job logs
$logs = Actions::getJobLogs('owner', 'repo', 456);

// Rerun a job
Actions::rerunJob('owner', 'repo', 456);
```

### Artifacts

```php
// List artifacts for a repository
$artifacts = Actions::listArtifactsForRepository('owner', 'repo');

// List artifacts for a workflow run
$artifacts = Actions::listArtifactsForWorkflowRun('owner', 'repo', 123);

// Get a specific artifact
$artifact = Actions::getArtifact('owner', 'repo', 789);

// Download an artifact
$zipContent = Actions::downloadArtifact('owner', 'repo', 789);

// Delete an artifact
Actions::deleteArtifact('owner', 'repo', 789);
```

## Data Transfer Objects

All responses are returned as clean, readonly DTOs:

### WorkflowRun

```php
$run->id;              // int
$run->name;            // string
$run->status;          // string
$run->conclusion;      // ?string
$run->headBranch;      // string
$run->headSha;         // string
$run->event;           // string
$run->runNumber;       // int
$run->htmlUrl;         // string
$run->createdAt;       // DateTime
$run->updatedAt;       // DateTime

// Helper methods
$run->isQueued();
$run->isInProgress();
$run->isCompleted();
$run->wasSuccessful();
$run->wasFailed();
$run->wasCancelled();
```

### Workflow

```php
$workflow->id;         // int
$workflow->name;       // string
$workflow->path;       // string
$workflow->state;      // string
$workflow->htmlUrl;    // string
$workflow->createdAt;  // DateTime

// Helper methods
$workflow->isActive();
$workflow->isDisabled();
```

### Job

```php
$job->id;              // int
$job->runId;           // int
$job->name;            // string
$job->status;          // string
$job->conclusion;      // ?string
$job->steps;           // array<Step>
$job->htmlUrl;         // string
$job->startedAt;       // DateTime
$job->completedAt;     // ?DateTime

// Helper methods
$job->isQueued();
$job->isInProgress();
$job->isCompleted();
$job->wasSuccessful();
$job->wasFailed();
```

### Artifact

```php
$artifact->id;                  // int
$artifact->name;                // string
$artifact->sizeInBytes;         // int
$artifact->archiveDownloadUrl;  // string
$artifact->expired;             // bool
$artifact->createdAt;           // DateTime
$artifact->expiresAt;           // ?DateTime

// Helper methods
$artifact->isExpired();
$artifact->sizeInMb();  // Returns size in megabytes
```

## Configuration

The package includes sensible defaults but can be customized via `config/github-actions.php`:

```php
return [
    'default_timeout' => env('GITHUB_ACTIONS_DEFAULT_TIMEOUT', 30),

    'rate_limit' => [
        'enabled' => env('GITHUB_ACTIONS_RATE_LIMIT', true),
        'max_attempts' => env('GITHUB_ACTIONS_MAX_ATTEMPTS', 5),
        'retry_delay' => env('GITHUB_ACTIONS_RETRY_DELAY', 1000),
    ],

    'cache' => [
        'enabled' => env('GITHUB_ACTIONS_CACHE_ENABLED', false),
        'ttl' => env('GITHUB_ACTIONS_CACHE_TTL', 300),
        'prefix' => env('GITHUB_ACTIONS_CACHE_PREFIX', 'github_actions'),
    ],
];
```

## Testing

```bash
composer test
```

## Code Quality

```bash
composer format    # Format code with Laravel Pint
composer analyse   # Run PHPStan analysis
```

## License

MIT License. See [LICENSE](LICENSE) for details.

## Credits

Built with love by the [Conduit UI](https://github.com/conduit-ui) team.
