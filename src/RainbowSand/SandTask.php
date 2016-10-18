<?php

namespace RainbowSand;

use pocketmine\scheduler\PluginTask;
use pocketmine\entity\Entity;

class SandTask extends PluginTask{

	public function __construct($main) {
		$this->sand = $main;
		parent::__construct($main);
	}

	public function onRun($ticks) {
		$sand = $this->sand->makeSand();
		$sand->spawnToAll();
	}
}
