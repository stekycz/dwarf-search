<?php

namespace DwarfSearch\Searching;

use Kdyby\Events\Subscriber;
use Kdyby\Monolog\CustomChannel;
use Kdyby\Monolog\Logger;
use Nette\Object;



class SearchDirectorListener extends Object implements Subscriber
{

	const SEARCH_DIRECTOR_CHANNEL = 'search';

	/**
	 * @var CustomChannel
	 */
	private $loggerChannel;



	/**
	 * @param Logger $logger
	 */
	public function __construct(Logger $logger)
	{
		$this->loggerChannel = $logger->channel(self::SEARCH_DIRECTOR_CHANNEL);
	}



	/**
	 * @return string[]
	 */
	public function getSubscribedEvents()
	{
		return [
			'DwarfSearch\Searching\SearchDirector::onSearch',
		];
	}



	/**
	 * @param Search $search
	 * @param array $results
	 */
	public function onSearch(Search $search, array $results)
	{
		$resultsCount = count($results);
		$message = "Searching '{$search->getInput()}' #{$search->getId()} with {$resultsCount} results.";

		$this->loggerChannel->addDebug($message);
	}

}
