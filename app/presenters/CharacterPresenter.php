<?php

namespace DwarfSearch\Presenters;

use DwarfSearch\Characters\Character;
use DwarfSearch\Characters\CharacterFinder;



class CharacterPresenter extends BasePresenter
{

	/**
	 * @var CharacterFinder
	 */
	private $characterFinder;

	/**
	 * @var Character|NULL
	 */
	private $character;



	/**
	 * @param CharacterFinder $characterFinder
	 */
	public function __construct(CharacterFinder $characterFinder)
	{
		parent::__construct();

		$this->characterFinder = $characterFinder;
	}



	/**
	 * @param string|NULL $slug
	 */
	public function actionDefault($slug)
	{
		$this->character = $this->characterFinder->findBySlug($slug);

		if ($this->character === NULL) {
			$this->error();
		}
	}



	public function renderDefault()
	{
		$this->template->character = $this->character;
	}

}
