<?php

namespace DwarfSearch\Components\Search;

use DwarfSearch\Searching\Search;
use DwarfSearch\Searching\SearchManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;



/**
 * @method onSavedSearch(Search $search)
 */
class SearchControl extends Control
{

	/**
	 * @var \Callable[]
	 */
	public $onSavedSearch = [];

	/**
	 * @var ITranslator
	 */
	private $translator;

	/**
	 * @var SearchManager
	 */
	private $searchManager;



	public function __construct(ITranslator $translator, SearchManager $searchManager)
	{
		parent::__construct();

		$this->translator = $translator;
		$this->searchManager = $searchManager;
	}



	public function render()
	{
		$this->template->render(__DIR__ . '/search.latte');
	}



	/**
	 * @return Form
	 */
	protected function createComponentSearchForm()
	{
		$form = new Form();
		$form->setTranslator($this->translator);
		$form->addProtection();

		$form->addText('search')
			->setRequired();

		$form->addSubmit('submit');

		$form->onSuccess[] = function (Form $form) {
			$search = $this->searchManager->saveSearch($form->getValues()->search);
			$this->onSavedSearch($search);
		};

		return $form;
	}

}



interface ISearchControlFactory
{

	/**
	 * @return SearchControl
	 */
	public function create();

}
