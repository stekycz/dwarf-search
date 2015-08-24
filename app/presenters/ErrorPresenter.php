<?php

namespace DwarfSearch\Presenters;

use Nette\Application\BadRequestException;
use Nette\Application\UI\Presenter;
use Tracy\ILogger;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;
use WebLoader\Nette\LoaderFactory;



class ErrorPresenter extends Presenter
{

	/** @var ILogger */
	private $logger;

	/**
	 * @var LoaderFactory
	 */
	private $loaderFactory;



	/**
	 * @param ILogger $logger
	 * @param LoaderFactory $loaderFactory
	 */
	public function __construct(ILogger $logger, LoaderFactory $loaderFactory)
	{
		parent::__construct();

		$this->logger = $logger;
		$this->loaderFactory = $loaderFactory;
	}



	/**
	 * @param  \Exception
	 * @return void
	 */
	public function renderDefault($exception)
	{
		if ($exception instanceof BadRequestException) {
			$code = $exception->getCode();
			$this->setView(in_array($code, [403, 404, 405, 410, 500]) ? $code : '4xx');

		} else {
			$this->setView('500');
			$this->logger->log($exception, ILogger::EXCEPTION);
		}

		if ($this->isAjax()) {
			$this->payload->error = TRUE;
			$this->terminate();
		}
	}



	/**
	 * @return CssLoader
	 */
	protected function createComponentCss()
	{
		return $this->loaderFactory->createCssLoader('default');
	}



	/**
	 * @return JavaScriptLoader
	 */
	protected function createComponentJs()
	{
		return $this->loaderFactory->createJavaScriptLoader('default');
	}

}
