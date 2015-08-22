<?php

namespace DwarfSearch\Presenters;

use DwarfSearch\Components\SearchBox\ISearchBoxControlFactory;
use DwarfSearch\Searching\Search;
use DwarfSearch\Searching\SearchDirector;
use DwarfSearch\Searching\SearchManager;
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
	 * @var SearchDirector
	 */
	private $searchDirector;



	/**
	 * @param SearchManager $searchManager
	 * @param SearchDirector $searchDirector
	 */
	public function __construct(SearchManager $searchManager, SearchDirector $searchDirector)
	{
		parent::__construct();

		$this->searchManager = $searchManager;
		$this->searchDirector = $searchDirector;
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
		$this->template->search = $this->search;
		$this->template->results = $this->searchDirector->search($this->search);
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
