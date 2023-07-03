<?php

namespace DMarti\ExamplesSymfony5\Command;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'app:create-magic-number',
    description: 'Creates a magic number! :o',
)]
class CreateMagicNumberCommand extends Command
{
    public function __construct(
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('max', InputArgument::OPTIONAL, 'Max number')
            ->addOption('log', null, InputOption::VALUE_NONE, 'Log the magic number');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $io = new SymfonyStyle($input, $output);

            $max = $input->getArgument('max') ?: '100';
            if (!ctype_digit($max)) {
                $io->error(sprintf('Invalid `max` argument: %s', $max));

                return Command::FAILURE;
            }
            $max = (int) $max;

            $magicNumber = rand(1, $max);

            if ($input->getOption('log')) {
                // you can quickly check this with: cat ./var/log/dev.log
                $this->logger->info('Creating a magic number (max: {max}): {magicNumber}', [
                    'max' => $max,
                    'magicNumber' => $magicNumber,
                ]);

                // try running this command with `--log -v` arguments
                if ($io->isVerbose()) {
                    $io->note('Magic number was logged.');
                }
            }

            $io->success(sprintf('MAGIC NUMBER: %d', $magicNumber));

            if ($io->confirm('Crash?', false)) {
                throw new Exception('@#$%^&*(!');
            }

            return Command::SUCCESS;
        } catch (Throwable $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
