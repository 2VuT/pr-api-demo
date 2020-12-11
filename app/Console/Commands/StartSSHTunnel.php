<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

class StartSSHTunnel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssh-tunnel:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open SSH Tunnel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $process = Process::fromShellCommandline(
            $this->script(
                env('SSH_HOST'),
                env('SSH_PORT'),
                env('SSH_KEY'),
                env('SSH_USER'),
                env('SSH_FORWARD_PORT')
            )
        )->setTimeout(10);

        try {
            $process = tap($process)->run();
        } catch (ProcessTimedOutException $e) {
            //
        }
    }

    protected function script($ipAddress, $port, $keyPath, $user, $forwardPort)
    {
        return implode(' ', [
            'nohup ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -N',
            '-i '.$keyPath,
            '-L '.$forwardPort,
            '-p '.$port,
            $user.'@'.$ipAddress,
            '>> /dev/null 2>&1 &',
        ]);
    }
}
