<?php

namespace DwarfSearch\Scenarios;

use DwarfSearch\Characters\Character;
use DwarfSearch\Episodes\Episode;
use DwarfSearch\Languages\Language;
use DwarfSearch\Seasons\Season;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\DI\Container;
use Nette\Utils\Strings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;



class ScreenplayImportCommand extends Command
{

	const EPISODE_ARGUMENT = 'episode';
	const SEASON_ARGUMENT = 'season';
	const LANGUAGE_ARGUMENT = 'language';

	const KNOWN_NON_CHARACTERS = [
		'SKUPINKA',
		'VŠICHNI',
	];

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @var EntityRepository
	 */
	private $languagesRepository;

	/**
	 * @var EntityRepository
	 */
	private $episodesRepository;

	/**
	 * @var EntityRepository
	 */
	private $seasonsRepository;

	/**
	 * @var string
	 */
	private $scenariosDir;

	/**
	 * @var EntityRepository
	 */
	private $charactersRepository;



	/**
	 * @param Container $container
	 * @param EntityManager $entityManager
	 */
	public function __construct(Container $container, EntityManager $entityManager)
	{
		parent::__construct('dwarfSearch:import');

		$this->entityManager = $entityManager;
		$this->seasonsRepository = $entityManager->getRepository(Season::class);
		$this->episodesRepository = $entityManager->getRepository(Episode::class);
		$this->languagesRepository = $entityManager->getRepository(Language::class);
		$this->charactersRepository = $entityManager->getRepository(Character::class);
		$this->scenariosDir = $container->expand('%appDir%/../scenarios');
	}



	/**
	 * @inheritdoc
	 */
	protected function configure()
	{
		$this->setName('dwarfSearch:import')
			->setDescription('Naimportuje data z TXT scénářů do databáze.')
			->addArgument(self::LANGUAGE_ARGUMENT, InputArgument::REQUIRED, 'Jazyk');
	}



	/**
	 * @inheritdoc
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$languageArgument = $input->getArgument(self::LANGUAGE_ARGUMENT);
		/** @var Language $language */
		if (($language = $this->languagesRepository->findOneBy(['shortCode' => $languageArgument])) === NULL) {
			$output->writeln("<error>Unknown language {$languageArgument}</error>");

			return 1;
		}

		/** @var Season $season */
		foreach ($this->seasonsRepository->findBy(['language' => $language]) as $season) {
			foreach ($season->getEpisodes() as $episode) {
				$file = "S{$season->getNumber()}E{$episode->getNumber()}{$language->getShortCode()}.txt";
				$handle = fopen($this->scenariosDir . '/' . $file, 'r');

				if ($handle) {
					$screenplayLine = 1;
					while (($line = fgets($handle)) !== FALSE) {
						$name = Strings::before($line, ':');

						$isCharacter = ($name && !in_array($name, self::KNOWN_NON_CHARACTERS, TRUE) && str_word_count($name, 0) < 3) ? TRUE : FALSE;

						$character = $isCharacter
							? $this->charactersRepository->findOneBy(['slug' => Strings::webalize($name), 'language' => $language])
							: NULL;
						if ($isCharacter && $character === NULL) {
							$character = new Character(Strings::capitalize($name), $language);

							$this->entityManager->persist($character);
							$this->entityManager->flush($character);;
						}
						$text = $isCharacter
							? str_replace($name . ': ', '', $line)
							: $line;

						$text = Strings::trim($text);

						if (empty($text)) {
							continue;
						}

						$screenplay = new Screenplay($episode, $text, $character, $screenplayLine, !$isCharacter);
						$this->entityManager->persist($screenplay);
						$this->entityManager->flush($screenplay);
						$screenplayLine++;
					}
					fclose($handle);
				} else {
					$output->writeln("<error>Scénář {$file} neexistuje!</error>");

					return 1;
				}
			}
		}

		return 0;

	}

}
