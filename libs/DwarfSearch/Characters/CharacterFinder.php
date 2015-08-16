<?php

namespace DwarfSearch\Characters;

use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Object;



class CharacterFinder extends Object
{

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @var EntityRepository
	 */
	private $charactersRepository;



	/**
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->charactersRepository = $entityManager->getRepository(Character::class);
	}



	/**
	 * @param string $slug
	 * @return Character|NULL
	 */
	public function findBySlug($slug)
	{
		return $this->charactersRepository->findOneBy([
			'slug' => $slug,
		]);
	}

}
