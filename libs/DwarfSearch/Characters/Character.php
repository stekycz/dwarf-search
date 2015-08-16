<?php

namespace DwarfSearch\Characters;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DwarfSearch\Languages\Language;
use DwarfSearch\Scenarios\Screenplay;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette\Object;
use Nette\Utils\Strings;



/**
 * @ORM\Entity()
 * @ORM\Table(name="characters", uniqueConstraints={
 *      @ORM\UniqueConstraint(columns={"slug", "language_id"})
 * })
 */
class Character extends Object
{

	use Identifier;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\OneToMany(targetEntity="DwarfSearch\Scenarios\Screenplay", mappedBy="character", cascade={"persist"})
	 * @var Screenplay[]|Collection
	 */
	private $scenarios;

	/**
	 * @ORM\ManyToOne(targetEntity="DwarfSearch\Languages\Language", cascade={"persist"})
	 * @var Language
	 */
	private $language;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $slug;



	/**
	 * @param string $name
	 * @param Language $language
	 */
	public function __construct($name, Language $language)
	{
		$this->name = $name;
		$this->language = $language;
		$this->slug = Strings::webalize($name);

		$this->scenarios = new ArrayCollection();
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



	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getName();
	}

}
