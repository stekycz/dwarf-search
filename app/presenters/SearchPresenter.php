<?php

namespace DwarfSearch\Presenters;

use DwarfSearch\Components\SearchBox\ISearchBoxControlFactory;
use DwarfSearch\Searching\Search;
use DwarfSearch\Searching\SearchManager;
use Elastica\Query\Bool;
use Elastica\Query\Fuzzy;
use Elastica\Query\Match;
use Elastica\Query\MatchPhrase;
use Elastica\Query\Term;
use Kdyby\ElasticSearch\Client;
use Nette\Application\UI\Multiplier;



class SearchPresenter extends BasePresenter
{

	/**
	 * @var SearchManager
	 */
	private $searchManager;

	/**
	 * @var Search|NULL
	 */
	private $search;

	/**
	 * @var Client
	 */
	private $client;



	/**
	 * @param SearchManager $searchManager
	 */
	public function __construct(SearchManager $searchManager, Client $client)
	{
		parent::__construct();

		$this->searchManager = $searchManager;
		$this->client = $client;
	}



	/**
	 * @param string|NULL $slug
	 */
	public function actionDefault($slug)
	{
		if (!$slug) {
			$this->error();
		}

		$this->search = $this->searchManager->search($slug);
		if ($this->search === NULL) {
			$this->error();
		}
	}



	/**
	 * @param string|NULL $slug
	 */
	public function renderDefault($slug)
	{
		$index = $this->client->getIndex('dwarf');
		$this->template->search = $this->search;

		$bool = new Bool();
		$match = new Match();
		$match->setField('text', $this->search->getInput());

		$bool->addMust($match);

		$searchResults = $index->search($bool, 50)->getResults();
		$results = [];
		foreach ($searchResults as $result) {
			$results[] = $result->getData();
		}
		$this->template->results = $results;
	}



	/**
	 * @param ISearchBoxControlFactory $factory
	 * @return Multiplier
	 */
	protected function createComponentSearchBox(ISearchBoxControlFactory $factory)
	{
		return $factory->create();
	}

}
