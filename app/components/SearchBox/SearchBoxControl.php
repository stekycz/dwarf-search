<?php

namespace DwarfSearch\Components\SearchBox;

use Nette\Application\UI\Control;



class SearchBoxControl extends Control
{

	public function __construct()
	{
		parent::__construct();
	}



	public function render(array $searchResult)
	{
		$this->template->result = $searchResult;

		$this->template->render(__DIR__ . '/searchBox.latte');
	}

}



interface ISearchBoxControlFactory
{

	/**
	 * @return SearchBoxControl
	 */
	public function create();

}
