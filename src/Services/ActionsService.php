<?php

declare(strict_types=1);

namespace ConduitUI\Action\Services;

use ConduitUI\GithubConnector\GithubConnector;
use ConduitUI\Action\Contracts\ActionsServiceInterface;
use ConduitUI\Action\Traits\ManagesArtifacts;
use ConduitUI\Action\Traits\ManagesJobs;
use ConduitUI\Action\Traits\ManagesWorkflowRuns;
use ConduitUI\Action\Traits\ManagesWorkflows;

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
