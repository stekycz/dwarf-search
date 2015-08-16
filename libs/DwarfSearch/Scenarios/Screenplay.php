<?php

namespace DwarfSearch\Scenarios;

use Doctrine\ORM\Mapping as ORM;
use DwarfSearch\Characters\Character;
use DwarfSearch\Episodes\Episode;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette\Object;



/**
 * @ORM\Entity()
 * @ORM\Table(name="scenarios")
 */
class Screenplay extends Object
{

	use Identifier;

	/**
	 * @ORM\ManyToOne(targetEntity="DwarfSearch\Episodes\Episode", inversedBy="scenarios", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=FALSE)
	 * @var Episode
	 */
	private $episode;

	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	private $text;

	/**
	 * @ORM\ManyToOne(targetEntity="DwarfSearch\Characters\Character", inversedBy="scenarios", cascade={"persist"})
	 * @var Character|NULL
	 */
	private $character;

	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	private $line;

	/**
	 * @ORM\Column(type="boolean", options={"default": FALSE})
	 * @var bool
	 */
	private $intro = FALSE;



	/**
	 * @param Episode $episode
	 * @param string $text
	 * @param Character|NULL $character
	 * @param int $line
	 * @param bool $intro
	 */
	public function __construct(
		Episode $episode, $text, Character $character = NULL,
		$line, $intro = FALSE
	) {
		$this->episode = $episode;
		$this->text = $text;
		$this->character = $character;
		$this->line = $line;
		$this->intro = $intro;
	}



	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}



	/**
	 * @return Character|NULL
	 */
	public function getCharacter()
	{
		if ($this->isIntro()) {
			return NULL;
		}

		return $this->character;
	}



	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->line;
	}



	/**
	 * @return bool
	 */
	public function isIntro()
	{
		return $this->intro === TRUE;
	}

}
