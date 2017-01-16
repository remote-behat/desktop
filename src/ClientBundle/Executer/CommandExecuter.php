<?php

namespace ClientBundle\Executer;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Process\Process;

class CommandExecuter
{
    /**
     * @param string $cmd
     *
     * @return array
     */
    public function execute($cmd)
    {
        $output = [
            Process::OUT => '',
            Process::ERR => ''
        ];

        $process = new Process($cmd);
        $process->start();
        $process->wait(function ($type, $buffer) use (&$output) {
            $output[$type] .= $buffer;
        });

        return $output;
    }

    /**
     * @param string $cmd
     *
     * @return StreamedResponse
     */
    public function executeAndStreamResponse($cmd)
    {
        $response = new StreamedResponse();
        $response->headers->add(['Content-type' => 'application/json']);

        $process = new Process($cmd);
        $process->start();
        $response->setCallback(function () use ($process) {
            $process->wait(function ($type, $buffer) {
                echo $buffer;
                flush();
                ob_flush();
            });
        });

        return $response;
    }
}
