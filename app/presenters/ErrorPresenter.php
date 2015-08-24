<?php

namespace DwarfSearch\Presenters;

use Nette\Application\BadRequestException;
use Tracy\ILogger;



class ErrorPresenter extends BasePresenter
{

	/** @var ILogger */
	private $logger;



	/**
	 * @param ILogger $logger
	 */
	public function __construct(ILogger $logger)
	{
		parent::__construct();

		$this->logger = $logger;
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

}
