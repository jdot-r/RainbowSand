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

use RainbowSand\RainbowSand;

class Main extends PluginBase{

	public $maxY = 10;
	public $minY = 5;
	public $distanceX = 5;
	public $distanceZ = 5;

	private $time = 1;

        public function onEnable() {

		Entity::registerEntity(RainbowSand::class,true);

		$server = Server::getInstance();
		$this->spawn = $server->getDefaultLevel()->getSpawn();

		$server->getScheduler()->scheduleRepeatingTask(new SandTask($this),$this->time*15);

        }


	public function makeSand() {

	   $randX = null;
 	   $randZ = null;

	   $sy = $this->spawn->y;
	   $sx = $this->spawn->x;
	   $sz = $this->spawn->z;

 	   $n1 = $sx + $this->set($this->distanceX); 
	   $n2 = $sz + $this->set($this->distanceZ);

	   if( $n1 < $sx ){$randX = mt_rand( $n1 , $sx);
	   }else{ 	   $randX = mt_rand( $sx , $n1);
	   }

	   if( $n2 < $sz ){$randZ = mt_rand( $n2 , $sz);
	   }else{ 	   $randZ = mt_rand( $sz , $n2);
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

	public function set($a) {

	 $n = mt_rand(1,2);

	    if($n === 1){ return $a;
	    }else{ return -$a;
	    }

	}

}
