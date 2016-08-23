<?php

namespace ClientBundle\Executer;

class CommandExecuter
{
    public function execute($cmd)
    {
        $proc = proc_open(
            $cmd,
            [
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w']
            ],
            $pipes
        );

        while (proc_get_status($proc)['running']) {
            usleep(500000);
        }

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        proc_close($proc);

        return [
            $stdout,
            $stderr
        ];
    }
}
