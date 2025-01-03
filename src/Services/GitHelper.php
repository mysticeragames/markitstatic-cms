<?php

namespace App\Services;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class GitHelper
{
    public function pull($dir)
    {
        // Pull
        $process = new Process(['git', '-C', $dir, 'pull']);
        $process->run();
        if(!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    public function addCommitPush($dir, $message = null)
    {
        if(empty($message)) {
            $message = 'deployment ' . date('Y-m-d H:i:s');
        }

        // Get current branch
        $process = new Process(['git', '-C', $dir, 'branch', '--show-current']);
        $process->run();
        if(!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $branch = trim($process->getOutput());

        // Add all files in working directory
        $process = new Process(['git', '-C', $dir, 'add', '.']);
        $process->run();
        if(!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Commit files
        $process = new Process(['git', '-C', $dir, 'commit', '-m', $message]);
        $process->run();
        if(!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Push
        $process = new Process(['git', '-C', $dir, 'push', '-u', 'origin', $branch]);
        $process->run();
        if(!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
