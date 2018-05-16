<?php

namespace App\Command;

use App\Services\Greeting;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HelloCommand extends Command
{
    /**
     * @var Greeting
     */
    private $greeting;

    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:say-hello')
            ->setDescription('Add a short description for your command')
            ->addArgument('name', InputArgument::REQUIRED, 'your name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('name');

        $io->success($this->greeting->greet($name) . ", commands eh..");
        $io->note('Thats good init!.');
    }
}
