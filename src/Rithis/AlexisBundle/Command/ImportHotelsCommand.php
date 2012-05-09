<?php

namespace Rithis\AlexisBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ImportHotelsCommand extends Command
{
    protected function configure()
    {
        $this->setName("alexis:import:hotels");
        $this->setDescription("Import hotels from Pegas Touristik");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = new Process(__DIR__ . "/../../../../console.py hotels");
        $process->setTimeout(60 * 30);
        $process->run(function ($type, $buffer) use ($output)
        {
            if ($type == Process::ERR) {
                $output->getErrorOutput()->writeln($buffer);
            } else {
                $output->writeln($buffer);
            }
        });
    }
}
