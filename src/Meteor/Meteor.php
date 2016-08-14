<?php

namespace Meteor;

use pocketmine\level\format\FullChunk;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\entity\Entity;
use pocketmine\entity\Projectile;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\level\Explosion;
use pocketmine\level\Position;

class Meteor extends Projectile{

	const NETWORK_ID = 85;

	public $width = 0.25;
	public $length = 0.25;
	public $height = 0.25;

	protected $gravity = 0.05;  //—Ž‚¿‚é‘¬“x
	protected $drag = 0.01;

	public function __construct(FullChunk $chunk, CompoundTag $nbt, Entity $shootingEntity = null){
		parent::__construct($chunk, $nbt, $shootingEntity);
	}

	public function onUpdate($currentTick){
		if($this->closed){
			return false;
		}

		$this->timings->startTiming();

		$hasUpdate = parent::onUpdate($currentTick);

		if($this->age > 1200 or $this->isCollided){
			$this->kill();
			$this->explode();
			$hasUpdate = true;
		}

		$this->timings->stopTiming();

		return $hasUpdate;
	}

	public function explode(){
		//$this->server->getPluginManager()->callEvent($ev = new ExplosionPrimeEvent($this, 4, $this->dropItem));
		$theX = $this->getX();
		$theY = $this->getY();
		$theZ = $this->getZ();
		$level = $this->getLevel();
		$thePosition = new Position($theX, $theY, $theZ, $level);
		$theExplosion = new Explosion($thePosition, 5, null);
		$theExplosion->explodeB();
                $impact = 1;
                $damage = 1;
}

	public function spawnTo(Player $player){

		$pk = new AddEntityPacket();
		$pk->type = 85;
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->metadata = [];
		$player->dataPacket($pk);
		parent::spawnTo($player);
	}
}