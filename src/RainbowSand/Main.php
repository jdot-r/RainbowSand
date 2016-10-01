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
	
	public $maxY = [];
	public $minY = [];
	public $distanceX = [];
	public $distanceZ = [];

	private $time = [];

        public function onEnable() {
		@mkdir($this->getDataFolder());
		if(!file_exists($this->getDataFolder(). "/config.yml")){
        	$config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
                	"maxY" => "1",
                	"minY" => "1",
			"distanceX" => "1",
                	"distanceZ" => "1",
			"x" => "1",
			"y" => "1",
			"z" => "1",
			"level" => "world"
		))->getAll();
		}
		$this->saveDefaultConfig();
		$maxY = $config->get("maxY");
		$minY = $config->get("minY");
		$distanceX = $config->get("distanceX");
		$distanceZ = $config->get("distanceZ");
		$sx = $config->get("x");
		$sy $config->get("y");
		$sz = $config->get("z");
		$level = $config->get("level");
		Entity::registerEntity(RainbowSand::class, true);

		$server = Server::getInstance();
		
		$spawn = $this->getServer()->getDefaultLevel()->getLevelByName();

		$server->getScheduler()->scheduleRepeatingTask(new SandTask($this), $this->time * 15);

        }


	public function makeSand() {
		$randX = null;
		$randZ = null;
		
		$level = $config->get("level");
		
	   	if($this->config->get("level" === ($this->spawn()))) {

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
			$sand = Entity::createEntity("RainbowSand", $level->getChunk($randX>>4, $randZ>>4) , $nbt);
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
