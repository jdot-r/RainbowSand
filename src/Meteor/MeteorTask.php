<?php

namespace Meteor;

use pocketmine\scheduler\PluginTask;
use pocketmine\entity\Entity;

class MeteorTask extends PluginTask{

	public function __construct($main) {

		$this->m = $main;
		parent::__construct($main);

	}

	public function onRun($ticks) {

		$meteor = $this->m->makeMeteor();
		$meteor->spawnToAll();
	}

}