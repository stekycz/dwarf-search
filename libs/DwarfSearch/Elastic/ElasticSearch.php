<?php

namespace DwarfSearch\Elastic;

use Kdyby\ElasticSearch\Client;
use Nette\Object;



class ElasticSearch extends Object
{

	/**
	 * @var Client
	 */
	private $client;



	public function __construct(Client $client)
	{
		$this->client = $client;
	}



	public function search($string, $lang = 'cs')
	{
		$index = $this->client->getIndex($lang . '_screenplay');
		$result = $index->search($string);
		$results = $result->getResults();

		return $results;

	}

}
