<?php

namespace DwarfSearch\Searching;

use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Object;
use Nette\Utils\Strings;



class SearchManager extends Object
{

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @var EntityRepository
	 */
	private $searchesRepository;



	/**
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->searchesRepository = $entityManager->getRepository(Search::class);
	}



	/**
	 * @param string $searchString
	 * @return Search
	 */
	public function saveSearch($searchString)
	{
		if ($search = $this->search(Strings::webalize($searchString))) {

			return $search;
		}

		$search = new Search($searchString);

		$this->entityManager->persist($search);
		$this->entityManager->flush($search);

		return $search;
	}



	/**
	 * @param string $slug
	 * @return Search|NULL
	 */
	public function search($slug)
	{
		return $this->searchesRepository->findOneBy([
			'slug' => $slug,
		]);
	}

}
