<?php

namespace DwarfSearch\Seasons;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DwarfSearch\Episodes\Episode;
use DwarfSearch\Languages\Language;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette\Object;



/**
 * @ORM\Entity()
 * @ORM\Table(name="seasons", uniqueConstraints={
 *      @ORM\UniqueConstraint(columns={"number", "language_id"})
 * })
 */
class Season extends Object
{

	use Identifier;

	/**
	 * @ORM\Column(type="smallint")
	 * @var int
	 */
	private $number;

	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	private $year;

	/**
	 * @ORM\ManyToOne(targetEntity="DwarfSearch\Languages\Language", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=FALSE)
	 * @var Language
	 */
	private $language;

	/**
	 * @ORM\OneToMany(targetEntity="DwarfSearch\Episodes\Episode", mappedBy="season", cascade={"persist"})
	 * @var Episode[]|Collection
	 */
	private $episodes;



	/**
	 * @param int $number
	 * @param int $year
	 * @param Language $language
	 */
	public function __construct($number, $year, Language $language)
	{
		$this->number = $number;
		$this->year = $year;
		$this->language = $language;

		$this->episodes = new ArrayCollection();
	}



	/**
	 * @return int
	 */
	public function getNumber()
	{
		return $this->number;
	}



	/**
	 * @return Episode[]
	 */
	public function getEpisodes()
	{
		return $this->episodes->toArray();
	}
}
