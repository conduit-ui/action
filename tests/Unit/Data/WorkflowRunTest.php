<?php

declare(strict_types=1);

use ConduitUI\Actions\Data\WorkflowRun;

it('can create a workflow run from array', function () {
    $data = [
        'id' => 123,
        'name' => 'CI',
        'workflow_id' => 456,
        'status' => 'completed',
        'conclusion' => 'success',
        'head_branch' => 'main',
        'head_sha' => 'abc123',
        'event' => 'push',
        'run_number' => 10,
        'run_attempt' => 1,
        'created_at' => '2025-12-10T00:00:00Z',
        'updated_at' => '2025-12-10T01:00:00Z',
        'run_started_at' => '2025-12-10T00:05:00Z',
        'html_url' => 'https://github.com/owner/repo/actions/runs/123',
    ];

    $run = WorkflowRun::fromArray($data);

    expect($run->id)->toBe(123);
    expect($run->name)->toBe('CI');
    expect($run->status)->toBe('completed');
    expect($run->conclusion)->toBe('success');
});

it('can check if workflow run is completed', function () {
    $data = [
        'id' => 123,
        'name' => 'CI',
        'workflow_id' => 456,
        'status' => 'completed',
        'conclusion' => 'success',
        'head_branch' => 'main',
        'head_sha' => 'abc123',
        'event' => 'push',
        'run_number' => 10,
        'run_attempt' => 1,
        'created_at' => '2025-12-10T00:00:00Z',
        'updated_at' => '2025-12-10T01:00:00Z',
        'html_url' => 'https://github.com/owner/repo/actions/runs/123',
    ];

    $run = WorkflowRun::fromArray($data);

    expect($run->isCompleted())->toBeTrue();
    expect($run->wasSuccessful())->toBeTrue();
    expect($run->wasFailed())->toBeFalse();
});

it('can check if workflow run is in progress', function () {
    $data = [
        'id' => 123,
        'name' => 'CI',
        'workflow_id' => 456,
        'status' => 'in_progress',
        'conclusion' => null,
        'head_branch' => 'main',
        'head_sha' => 'abc123',
        'event' => 'push',
        'run_number' => 10,
        'run_attempt' => 1,
        'created_at' => '2025-12-10T00:00:00Z',
        'updated_at' => '2025-12-10T01:00:00Z',
        'html_url' => 'https://github.com/owner/repo/actions/runs/123',
    ];

    $run = WorkflowRun::fromArray($data);

    expect($run->isInProgress())->toBeTrue();
    expect($run->isCompleted())->toBeFalse();
});
