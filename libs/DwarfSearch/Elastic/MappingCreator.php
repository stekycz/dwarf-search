<?php

namespace DwarfSearch\Elastic;

use Elastica\Index;
use Elastica\Type;
use Elastica\Type\Mapping;
use Kdyby\ElasticSearch\Client;
use Nette\Object;



class MappingCreator extends Object
{

	const ELASTIC_INDEX = 'dwarf';
	const ELASTIC_TYPE = 'screenplay';

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var int
	 */
	private $shards = 3;

	/**
	 * @var int
	 */
	private $replicas = 1;



	/**
	 * @param Client $client
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}



	/**
	 * @param int $shards
	 */
	public function setShards($shards)
	{
		$this->shards = $shards;
	}



	/**
	 * @param int $replicas
	 */
	public function setReplicas($replicas)
	{
		$this->replicas = $replicas;
	}



	/**
	 * @param bool $reCreate
	 * @throws CannotRecreateIndexException
	 */
	public function create($reCreate = FALSE)
	{
		$index = $this->getIndex();
		if ($index->exists() && $reCreate === FALSE) {
			throw new CannotRecreateIndexException("Cannot recreate index. Index exists and you dont want to recreate it.");
		}

		$index->create($this->getIndexSettings(), [
			'recreate' => $reCreate,
		]);

		$screenplay = $index->getType('cs_' . self::ELASTIC_TYPE);
		$screenplay->setMapping($this->getTypeMapping($screenplay, 'cs'));

		self::sleep();
	}



	/**
	 * @return Index
	 */
	private function getIndex()
	{
		return $this->client->getIndex(self::ELASTIC_INDEX);
	}



	/**
	 * @return array
	 */
	private function getIndexSettings()
	{
		return [
			'number_of_shards' => $this->shards,
			'number_of_replicas' => $this->replicas,
			'settings' => [
				'analysis' => $this->getIndexAnalysis()
			],
		];
	}



	/**
	 * @return array
	 */
	private function getIndexAnalysis()
	{
		return [
			'filter' => [
				'cs_hunspell' => [
					'type' => 'hunspell',
					'locale' => 'cs_CZ',
					'dedup' => TRUE,
					'recursion_level' => 0
				],
				'cs_synonyms' => [
					'type' => 'synonym',
					'synonyms' => [
						"gazpacho,gazpáčo,gaspáčo",
					],
				],
				'czech_stemmer' => [
					'type' => 'stemmer',
					'language' => 'czech',
				],
			],
			'analyzer' => [
				'cs' => [
					'type' => 'custom',
					'tokenizer' => 'standard',
					'filter' => [
						"lowercase",
						"cs_hunspell",
						'cs_synonyms',
						'czech_stemmer',
						'asciifolding',
					]
				],
			],
		];
	}



	/**
	 * @param Type $type
	 * @param string $analyzer
	 * @return Mapping
	 */
	private function getTypeMapping(Type $type, $analyzer)
	{
		$mapping = new Mapping($type);
		$mapping->setParam('analyzer', $analyzer);
		$mapping->setProperties([
			'text' => [
				'type' => 'string',
				'index' => 'analyzed',
				'analyzer' => $analyzer,
			],
			'episode' => [
				'type' => 'object',
				'properties' => [
					'id' => [
						'type' => 'integer',
					],
					'number' => [
						'type' => 'integer',
					],
					'name' => [
						'type' => 'string',
						'index' => 'analyzed',
						'analyzer' => $analyzer,
					],
					'slug' => [
						'type' => 'string',
						'index' => 'not_analyzed',
					],
				],
			],
			'season' => [
				'type' => 'object',
				'parameters' => [
					'id' => [
						'type' => 'integer',
					],
					'number' => [
						'type' => 'integer',
					],
				],
			],
			'character' => [
				'type' => 'object',
				'properties' => [
					'name' => [
						'type' => 'string',
						'index' => 'not_analyzed',
					],
					'slug' => [
						'type' => 'string',
						'index' => 'not_analyzed',
					],
				],
			],
			'line' => [
				'type' => 'integer',
			],
		]);

		return $mapping;
	}



	/**
	 * @param int $sleep
	 */
	private static function sleep($sleep = 1)
	{
		sleep($sleep);
	}

}
