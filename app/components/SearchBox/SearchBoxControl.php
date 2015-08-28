<?php

namespace DwarfSearch\Components\SearchBox;

use Elastica\Result;
use Nette\Application\UI\Control;
use Nette\Utils\Html;



class SearchBoxControl extends Control
{

	public function __construct()
	{
		parent::__construct();
	}



	/**
	 * @param Result $result
	 */
	public function render(Result $result)
	{
		$this->template->result = $result->getData();
		$this->template->highlightedText = $this->getHighlightingTextFromResult($result);

		$this->template->render(__DIR__ . '/searchBox.latte');
	}



	/**
	 * @param Result $result
	 * @return Html
	 */
	private function getHighlightingTextFromResult(Result $result)
	{
		$highlights = $result->getHighlights();

		return Html::el()->setHtml(reset($highlights['text']));
	}

}



interface ISearchBoxControlFactory
{

	/**
	 * @return SearchBoxControl
	 */
	public function create();

}
