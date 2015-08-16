<?php

namespace DwarfSearch\Episodes;

use Doctrine\ORM\NoResultException;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Object;



class EpisodesFinder extends Object
{

	/**
	 * @var EntityRepository
	 */
	private $episodesRepository;



	/**
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		$this->episodesRepository = $entityManager->getRepository(Episode::class);
	}



	/**
	 * @param int $id
	 * @return Episode|NULL
	 */
	public function find($id)
	{
		try {
			return $this->episodesRepository->createQueryBuilder('e')
				->innerJoin('e.scenarios', 'scenarios')->addSelect('scenarios')
				->innerJoin('e.season', 'season')->addSelect('season')
				->leftJoin('scenarios.character', 'character')->addSelect('character')
				->andWhere('e.id = :id')->setParameter('id', $id)
				->addOrderBy('scenarios.line')
				->getQuery()
				->getSingleResult();
		} catch (NoResultException $e) {
			return NULL;
		}
	}

}
