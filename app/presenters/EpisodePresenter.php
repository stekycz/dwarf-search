<?php

namespace DwarfSearch\Presenters;

use DwarfSearch\Episodes\Episode;
use DwarfSearch\Episodes\EpisodesFinder;



class EpisodePresenter extends BasePresenter
{

	/**
	 * @var EpisodesFinder
	 */
	private $episodesFinder;

	/**
	 * @var Episode|NULL
	 */
	private $episode;



	/**
	 * @param EpisodesFinder $episodesFinder
	 */
	public function __construct(EpisodesFinder $episodesFinder)
	{
		parent::__construct();

		$this->episodesFinder = $episodesFinder;
	}



	/**
	 * @param string|NULL $id
	 */
	public function actionDefault($id)
	{
		if ($id === NULL) {
			$this->error();
		}

		$this->episode = $this->episodesFinder->find((int) $id);
		if ($this->episode === NULL) {
			$this->error();
		}
	}



	/**
	 * @param string|NULL $id
	 */
	public function renderDefault($id)
	{
		$this->template->episode = $this->episode;
	}

}
