<?php
namespace <%- VendorName %>\<%- ExtKey %>\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;

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

		$this->addOption(
			'dry-run',
			'd',
			InputOption::VALUE_NONE,
			'Dry-Run?'
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
		// For more styles/helpers see: https://www.typo3lexikon.de/typo3-tutorials/core/console.html

		$io = new SymfonyStyle($input, $output);
		$io->title($this->getDescription());

		$this->dryRun = (bool)$input->getOption('dry-run');

		if ($this->dryRun) {
			$io->text('No worries, it\'s a dry-run!');
		}

		for ($i = 0; $i < $input->getOption('iterations'); $i++) {
			$io->text($input->getArgument('text'));
		}

		$records = $this->getRecords();
		$io->text('It\'s ' . count($records) . ' tt_content records with a header that you\'ve got in your DB. Nice ;-)');
	}

	protected function getRecords()
	{
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
		$queryBuilder->getRestrictions()
			->removeAll()
			->add(GeneralUtility::makeInstance(DeletedRestriction::class));

		$rows = $queryBuilder
			->select($fields)
			->from('tt_content')
			->where($queryBuilder->expr()->neq('header', $queryBuilder->createNamedParameter('')))
			->execute()
			->fetchAll();

		return $rows;
	}
}
