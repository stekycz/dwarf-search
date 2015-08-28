<?php

namespace DwarfSearch\Searching;

use Closure;
use Elastica\Index;
use Elastica\Query;
use Elastica\Query\Bool;
use Elastica\Query\Match;
use Elastica\Result;
use Kdyby\ElasticSearch\Client;
use Nette\Object;



/**
 * @method onSearch(Search $search, array $results)
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
	 * @return Result[]
	 */
	public function search(Search $search)
	{
		$bool = new Bool();
		$match = new Match();
		$match->setField('text', $search->getInput());
		$bool->addMust($match);

		$query = new Query();
		$query->setQuery($bool);
		$query->setHighlight([
			'pre_tags' => [
				'<mark>',
			],
			'post_tags' => [
				'</mark>',
			],
			'fields' => [
				'text' => [
					'highlight_query' => [
						$bool->toArray(),
					],
				],
			],
		]);

		$results = $this->getIndex()->search($query, 50)->getResults();

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
