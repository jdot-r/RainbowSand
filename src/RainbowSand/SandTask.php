<?php

namespace RainbowSand;

use pocketmine\scheduler\PluginTask;
use pocketmine\entity\Entity;

class SandTask extends PluginTask{

	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
		parent::__construct($plugin);
	}

	public function onRun($ticks) {
		$sand = $this->plugin->makeSand();
		$sand->spawnToAll();
	}
}
