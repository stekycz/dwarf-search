<?php

namespace DwarfSearch\Episodes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DwarfSearch\Scenarios\Screenplay;
use DwarfSearch\Seasons\Season;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette\Object;



/**
 * @ORM\Entity()
 * @ORM\Table(name="episodes", uniqueConstraints={
 *      @ORM\UniqueConstraint(columns={"season_id", "slug"})
 * }, indexes={
 *      @ORM\Index(columns={"slug"}),
 *      @ORM\Index(columns={"name"})
 * })
 */
class Episode extends Object
{

	use Identifier;

	/**
	 * @ORM\ManyToOne(targetEntity="DwarfSearch\Seasons\Season", inversedBy="episodes", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=FALSE)
	 * @var Season
	 */
	private $season;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	private $number;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $slug;

	/**
	 * @ORM\OneToMany(targetEntity="DwarfSearch\Scenarios\Screenplay", mappedBy="episode", cascade={"persist"})
	 * @var Screenplay[]|Collection
	 */
	private $scenarios;



	/**
	 * @param Season $season
	 * @param string $name
	 * @param int $number
	 */
	public function __construct(Season $season, $name, $number)
	{
		$this->season = $season;
		$this->name = $name;

		$this->scenarios = new ArrayCollection();
		$this->number = $number;
	}



	/**
	 * @return int
	 */
	public function getNumber()
	{
		return $this->number;
	}



	/**
	 * @return Screenplay[]
	 */
	public function getScenarios()
	{
		return $this->scenarios->toArray();
	}



	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}



	/**
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}

}
