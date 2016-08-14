<?php

namespace Meteor;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\math\Vector3;
use pocketmine\level\Level;

use pocketmine\entity\Entity;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;

use Meteor\Meteor;

class Main extends PluginBase{

	public $maxY = 15;  //最高高度（スポーンの高さから）
	public $minY = 10;   //最低高度（スポーンの高さから）
	public $distanceX = 30; //スポーンからの距離
	public $distanceZ = 30; //スポーンからの距離

	private $time = 1; //毎秒

        public function onEnable() {

		Entity::registerEntity(Meteor::class,true);

		$server = Server::getInstance();
		$this->spawn = $server->getDefaultLevel()->getSpawn();

		$server->getScheduler()->scheduleRepeatingTask(new MeteorTask($this),$this->time*15);

        }


	public function makeMeteor() {

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

		$meteor = Entity::createEntity("Meteor",Server::getInstance()->getDefaultLevel()->getChunk($randX>>4,$randZ>>4) , $nbt);

		return $meteor;

	}

	public function set($a) {

	 $n = mt_rand(1,2);

	    if($n === 1){ return $a;
	    }else{ return -$a;
	    }

	}

}
