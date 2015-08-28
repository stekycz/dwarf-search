<?php

namespace DwarfSearch\Searching;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette\Object;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;



/**
 * @ORM\Entity()
 * @ORM\Table(name="searches", indexes={
 *      @ORM\Index(columns={"slug"})
 * })
 */
class Search extends Object
{

	use Identifier;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $input;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $slug;

	/**
	 * @ORM\Column(type="datetime")
	 * @var DateTime
	 */
	private $searchTime;



	/**
	 * @param string $input
	 * @param \DateTime|NULL $searchTime
	 */
	public function __construct($input, \DateTime $searchTime = NULL)
	{
		$this->input = $input;
		$this->slug = Strings::webalize($input);
		$this->searchTime = DateTime::from($searchTime);
	}



	/**
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}



	/**
	 * @return string
	 */
	public function getInput()
	{
		return $this->input;
	}



	/**
	 * @return string
	 */
	public function getInputWithoutAccents()
	{
		$input = Strings::toAscii($this->getInput());
		$input = preg_replace('#[^a-z0-9]+#i', ' ', $input);
		$input = trim($input, ' ');

		return $input;
	}



	/**
	 * @return DateTime
	 */
	public function getSearchTime()
	{
		return clone $this->searchTime;
	}

}
