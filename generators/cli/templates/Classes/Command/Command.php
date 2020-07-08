<?php
namespace <%- VendorName %>\<%- ExtKey %>\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;

class <%- Command %>Command extends Command
{
	/**
	 * @var SymfonyStyle
	 */
	protected $io = null;

	/**
	 * @var []
	 */
	protected $conf = null;

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

		if ($output->isVerbose()) {
			$this->io = new SymfonyStyle($input, $output);
			$this->io->title($this->getDescription());
		}

		$this->conf = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['<%- ext_key %>'];

		$this->dryRun = (bool)$input->getOption('dry-run');

		if ($this->dryRun) {
			$io->text('No worries, it\'s a dry-run!');
		}

		for ($i = 0; $i < $input->getOption('iterations'); $i++) {
			$io->text($input->getArgument('text'));
		}

		// Interactive question
		$choice = $io->choice(
			'How to proceed?',
			[
				'Skip',
				'Override',
			],
			'Skip'
		);
		if ($choice === 'Override') {
			$this->outputLine('<comment>It\'s a override, yes!</>');
		} else {
			$this->outputLine('<fg=green>Hm, it\'s a skip, bummer!</>');
		}

		$records = $this->getRecords();
		$this->outputLine('It\'s ' . count($records) . ' tt_content records with a header that you\'ve got in your DB. Nice ;-)');
	}

	/**
	 * Outputs specified text to the console window and appends a line break
	 *
	 * @param  string $string Text to output
	 * @param  array  $arguments Optional arguments to use for sprintf
	 * @return void
	 */
	protected function outputLine(string $string, $arguments = [])
	{
		if ($this->io) {
			$this->io->text($string);
		}
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
