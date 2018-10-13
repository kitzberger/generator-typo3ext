<?php
namespace <%- VendorName %>\<%- ExtKey %>\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class <%- Command %>Command extends Command
{
	/**
	 * Configure the command by defining the name
	 */
	protected function configure()
	{
		$this->setDescription('This is <%- Command %>Command speaking!');

		// See https://symfony.com/doc/current/console/input.html
		$this->addArgument(
			'text',
			InputArgument::OPTIONAL, // OPTIONAL, REQUIRED, IS_ARRAY
			'If set, then this will be printed Out.'
		);

		$this->addOption(
			'iterations',
			null,
			InputOption::VALUE_REQUIRED,
			'How many times should the message be printed?',
			1
		);
	}

	/**
	 * Executes the command
	 *
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$io = new SymfonyStyle($input, $output);
		$io->title($this->getDescription());

		for ($i = 0; $i < $input->getOption('iterations'); $i++) {
			$io->text($input->getArgument('text'));
		}
	}
}
