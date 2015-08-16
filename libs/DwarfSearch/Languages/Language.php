<?php

namespace DwarfSearch\Languages;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette\Object;



/**
 * @ORM\Entity(readOnly=TRUE)
 * @ORM\Table(name="languages", uniqueConstraints={
 *      @ORM\UniqueConstraint(columns={"short_code"})
 * })
 */
class Language extends Object
{

	const LANGUAGE_CZECH = 'cs';
	const LANGUAGE_ENGLISH = 'en';

	const AVAILABLE_LANGUAGES = [
		self::LANGUAGE_CZECH,
		self::LANGUAGE_ENGLISH,
	];

	use Identifier;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=3)
	 * @var string
	 */
	private $shortCode;



	/**
	 * @return string
	 */
	public function getShortCode()
	{
		return $this->shortCode;
	}
}
