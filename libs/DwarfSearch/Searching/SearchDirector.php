<?php

namespace DwarfSearch\Searching;

use Closure;
use Elastica\Index;
use Elastica\Query\Bool;
use Elastica\Query\Match;
use Kdyby\ElasticSearch\Client;
use Nette\Object;



/**
 * @method onSearch(Search $search, $results)
 */
class SearchDirector extends Object
{

	const ELASTIC_INDEX_NAME = 'dwarf';

	/**
	 * @var Closure[]
	 */
	public $onSearch = [];

	/**
	 * @var Client
	 */
	private $client;



	/**
	 * @param Client $client
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}



	/**
	 * @param Search $search
	 * @return array
	 */
	public function search(Search $search)
	{
		$bool = new Bool();
		$match = new Match();
		$match->setField('text', $search->getInput());
		$bool->addMust($match);

		$results = [];
		foreach ($this->getIndex()->search($bool, 50)->getResults() as $result) {
			$results[] = $result->getData();
		}

		$this->onSearch($search, $results);

		return $results;
	}



	/**
	 * @return Index
	 */
	private function getIndex()
	{
		return $this->client->getIndex(self::ELASTIC_INDEX_NAME);
	}

}
