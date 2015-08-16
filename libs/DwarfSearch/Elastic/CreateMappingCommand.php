<?php

namespace DwarfSearch\Elastic;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



class CreateMappingCommand extends Command
{

	const RECREATE_ARGUMENT = 'reCreate';

	/**
	 * @var MappingCreator
	 */
	private $mappingCreator;



	/**
	 * @param MappingCreator $mappingCreator
	 */
	public function __construct(MappingCreator $mappingCreator)
	{
		parent::__construct();

		$this->mappingCreator = $mappingCreator;
	}



	public function configure()
	{
		$this->setName('dwarfSearch:createMapping')
			->addOption(self::RECREATE_ARGUMENT, 'r', InputOption::VALUE_NONE);
	}



	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$reCreate = (bool) $input->getOption(self::RECREATE_ARGUMENT);

		$this->mappingCreator->create($reCreate);

		return 0;
	}

}
