<?php

declare(strict_types=1);

namespace ConduitUI\Actions\Services;

use ConduitUI\GithubConnector\GithubConnector;
use ConduitUI\Actions\Contracts\ActionsServiceInterface;
use ConduitUI\Actions\Traits\ManagesArtifacts;
use ConduitUI\Actions\Traits\ManagesJobs;
use ConduitUI\Actions\Traits\ManagesWorkflowRuns;
use ConduitUI\Actions\Traits\ManagesWorkflows;

class ActionsService implements ActionsServiceInterface
{
    use ManagesArtifacts;
    use ManagesJobs;
    use ManagesWorkflowRuns;
    use ManagesWorkflows;

    public function __construct(
        private readonly GithubConnector $connector
    ) {}
}
