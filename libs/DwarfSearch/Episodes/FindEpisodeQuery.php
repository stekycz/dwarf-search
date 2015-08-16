<?php

namespace DwarfSearch\Episodes;

use Doctrine\ORM\QueryBuilder;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;



class FindEpisodeQuery extends QueryObject
{

	/**
	 * @var int
	 */
	private $id;



	/**
	 * @param int $id
	 */
	public function __construct($id)
	{
		parent::__construct();

		$this->id = $id;
	}



	/**
	 * @param Queryable $repository
	 * @return QueryBuilder
	 */
	protected function doCreateQuery(Queryable $repository)
	{
		return $repository;
	}

}
