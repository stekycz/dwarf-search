<?php

namespace DwarfSearch\Presenters;

use DwarfSearch\Components\Search\ISearchControlFactory;
use DwarfSearch\Components\Search\SearchControl;
use DwarfSearch\Searching\Search;
use Kdyby\Autowired\AutowireComponentFactories;
use Kdyby\Autowired\AutowireProperties;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Presenter;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;
use WebLoader\Nette\LoaderFactory;



abstract class BasePresenter extends Presenter
{

	/**
	 * @var string
	 * @persistent
	 */
	public $locale;

	use AutowireProperties;
	use AutowireComponentFactories;

	/**
	 * @var Translator
	 * @autowire
	 */
	protected $translator;

	/**
	 * @var LoaderFactory
	 * @autowire
	 */
	protected $webLoader;



	protected function startup()
	{
		parent::startup();

		$this->getSession()->start();
	}



	protected function beforeRender()
	{
		parent::beforeRender();
		$this->template->locale = $this->locale;
	}



	/**
	 * @return CssLoader
	 */
	protected function createComponentCss()
	{
		return $this->webLoader->createCssLoader('default');
	}



	/**
	 * @return JavaScriptLoader
	 */
	protected function createComponentJs()
	{
		return $this->webLoader->createJavaScriptLoader('default');
	}



	/**
	 * @param ISearchControlFactory $factory
	 * @return SearchControl
	 */
	protected function createComponentSearch(ISearchControlFactory $factory)
	{
		$control = $factory->create();

		$control->onSavedSearch[] = function (Search $search) {
			$this->redirect(':Search:default', ['slug' => $search->getSlug()]);
		};

		return $control;
	}

}
