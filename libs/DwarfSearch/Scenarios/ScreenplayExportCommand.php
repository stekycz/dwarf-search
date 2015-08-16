<?php

namespace DwarfSearch\Scenarios;

use DwarfSearch\Languages\Language;
use DwarfSearch\Seasons\Season;
use Elastica\Document;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Kdyby\ElasticSearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;



class ScreenplayExportCommand extends Command
{

	const LANGUAGE_ARGUMENT = 'lang';
	const SEASON_ARGUMENT = 'season';

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @var Client
	 */
	private $elastic;

	/**
	 * @var EntityRepository
	 */
	private $languagesRepository;

	/**
	 * @var EntityRepository
	 */
	private $seasonsRepository;



	/**
	 * @param EntityManager $entityManager
	 * @param Client $elastic
	 */
	public function __construct(EntityManager $entityManager, Client $elastic)
	{
		parent::__construct();

		$this->entityManager = $entityManager;
		$this->elastic = $elastic;
		$this->seasonsRepository = $entityManager->getRepository(Season::class);
		$this->languagesRepository = $entityManager->getRepository(Language::class);
	}



	/**
	 * @inheritdoc
	 */
	protected function configure()
	{
		$this->setName('dwarfSearch:export')
			->setDescription('Vyexportuje scénáře do Elasticu.')
			->addArgument(self::LANGUAGE_ARGUMENT, InputArgument::REQUIRED);
	}



	/**
	 * @inheritdoc
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		ini_set('memory_limit', '4G');

		$lang = $input->getArgument(self::LANGUAGE_ARGUMENT);
		/** @var Language $language */
		if (($language = $this->languagesRepository->findOneBy(['shortCode' => $lang])) === NULL) {
			$output->writeln("<error>Unknown language {$lang} given!");

			return 1;
		}

		$index = $this->elastic->getIndex('dwarf');
		$type = $index->getType($language->getShortCode() . '_screenplay');

		/** @var Season[] $seasons */
		$seasons = $this->seasonsRepository->findBy(['language' => $language]);
		foreach ($seasons as $season) {
			$output->writeln("Importing season {$season->getNumber()}...");
			foreach ($season->getEpisodes() as $episode) {
				foreach ($episode->getScenarios() as $screenplay) {
					if ($screenplay->getCharacter() !== NULL) {
						$document = new Document($screenplay->getId(), [
							'text' => $screenplay->getText(),
							'episode' => [
								'id' => $episode->getId(),
								'number' => $episode->getNumber(),
								'name' => $episode->getName(),
								'slug' => $episode->getSlug(),
							],
							'season' => [
								'id' => $season->getId(),
								'number' => $season->getNumber(),
							],
							'character' => [
								'name' => $screenplay->getCharacter()->getName(),
								'slug' => $screenplay->getCharacter()->getSLug()
							],
							'line' => $screenplay->getLine(),
						], $type, $index);
						$type->addDocument($document);
					}
				}
			}
		}
	}

}
