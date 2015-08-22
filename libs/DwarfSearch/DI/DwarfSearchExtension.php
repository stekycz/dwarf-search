<?php

namespace DwarfSearch\DI;

use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;



class DwarfSearchExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		foreach ($this->loadFromFile(__DIR__ . '/listeners.neon') as $listener) {
			$builder->addDefinition($this->prefix('listener'))
				->setClass($listener)
				->addTag(EventsExtension::TAG_SUBSCRIBER);
		}
	}

}
