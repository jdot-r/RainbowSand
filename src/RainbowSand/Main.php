<?php

namespace RainbowSand;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\math\Vector3;
use pocketmine\level\Level;

use pocketmine\entity\Entity;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;

use pocketmine\utils\Config;

use RainbowSand\RainbowSand;

class Main extends PluginBase{
	
	private $config = $this->getDataFolder(). "config.yml";
	public $maxY = $config->get("maxY");
	public $minY = $config->get("minY");
	public $distanceX = $config->get("distanceX");
	public $distanceZ = $config->get("distanceZ");

	private $time = $config->get("time");

        public function onEnable() {
		Entity::registerEntity(RainbowSand::class, true);

		$server = Server::getInstance();
		
		$this->config = new Config($this->getDataFolder("config.yml", Config::YAML));
		
		$spawn = $this->getServer()->getDefaultLevel()->getLevelByName();

		$server->getScheduler()->scheduleRepeatingTask(new SandTask($this), $this->time * 15);

        }


	public function makeSand() {
		$randX = null;
		$randZ = null;
	   
	   	$sx = $this->config->get("x");
	   	$sy = $this->config->get("y");
	   	$sz = $this->config->get("z");
		
	   	if($this->config->get("level" === $this->spawn)) {

 	   		$n1 = $sx + $this->set($this->distanceX); 
	   		$n2 = $sz + $this->set($this->distanceZ);

	   		if( $n1 < $sx ){$randX = mt_rand( $n1 , $sx);
				       }else{
				$randX = mt_rand( $sx , $n1);
			}

	   		if( $n2 < $sz ){$randZ = mt_rand( $n2 , $sz);
	   			}else{
				$randZ = mt_rand( $sz , $n2);
			}
			$server = Server::getInstance();
			$randY = mt_rand( $sy + $this->minY , $sy + $this->maxY );
			$nbt = new CompoundTag("", [
				"Pos" => new ListTag("Pos", [
				new DoubleTag("" , $randX ),
				new DoubleTag("" , $randY ),
				new DoubleTag("" , $randZ )
			]),
				"Motion" => new ListTag("Motion", [
				new DoubleTag(""),
				new DoubleTag(""),
				new DoubleTag("")
			]),
			"Rotation" => new ListTag("Rotation", [
				new FloatTag(""),
				new FloatTag("" , 90)
			]),
			]);
			$sand = Entity::createEntity("RainbowSand", $server->getDefaultLevel()->getChunk($randX>>4,$randZ>>4) , $nbt);
			return $sand;
		}
		   }

	public function set($a) {

	 $n = mt_rand(1,2);

	    if($n === 1){ return $a;
	    }else{ return -$a;
	    }

	}

}
