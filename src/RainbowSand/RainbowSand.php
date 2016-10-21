<?php

namespace RainbowSand;

use pocketmine\level\format\FullChunk;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\Network;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\entity\Entity;
use pocketmine\level\Position;

class RainbowSand extends Entity{

	const NETWORK_ID = 66;

	public $width = 0.25;
	public $length = 0.25;
	public $height = 0.25;

	protected $gravity = 0.05;
	protected $drag = 0.01;

	public function onUpdate($currentTick){
		if($this->closed){
			return false;
		}
		/* Keep this commented out for listing the colors
		$r = [255, 0, 0] //red #FF0000
		$o = [255, 127, 0]; //orange #FF7F00
		$y = [255, 255, 0]; //yellow #FFFF00
		$g = [0, 255, 0]; //green #FFFF00
		$b = [0, 0, 255]; //blue #0000FF
		$v = [75, 0, 130]; //violet #4B0082
		$p = [143, 0, 255]; //purple #8F00FF
		*/
		$thispos0 = new Vector3($this->x, $this->y, $this->z);
		
		$thispos1 = new Vector3($this->x + 1, $this->y, $this->z);
		
		$thispos2 = new Vector3($this->x + 2, $this->y, $this->z);
		
		$dust1 = new \pocketmine\level\particle\DustParticle(new \pocketmine\math\Vector3($thispos0), 255, 0, 0)); // RED
		$dust2 = new \pocketmine\level\particle\DustParticle(new \pocketmine\math\Vector3($thispos1), 0, 255, 0)); // GREEN
		$dust3 = new \pocketmine\level\particle\DustParticle(new \pocketmine\math\Vector3($thispos2), 0, 0, 255)); // BLUE
		
		$dust1->setComponents($thispos0);
		$dust2->setComponents($thispos1);
		$dust3->setComponents($thispos2);
		
		$this->level->addParticle($dust1($this->add(
			$this->width / 2 + mt_rand(-100, 100) / 500,
			$this->height / 2 + mt_rand(-100, 100) / 500,
			$this->width / 2 + mt_rand(-100, 100) / 500)));
		$this->level->addParticle($dust2($this->add(
			$this->width / 2 + mt_rand(-100, 100) / 500,
			$this->height / 2 + mt_rand(-100, 100) / 500,
			$this->width / 2 + mt_rand(-100, 100) / 500)));
		$this->level->addParticle($dust3($this->add(
			$this->width / 2 + mt_rand(-100, 100) / 500,
			$this->height / 2 + mt_rand(-100, 100) / 500,
			$this->width / 2 + mt_rand(-100, 100) / 500)));

		$this->timings->startTiming();

		$hasUpdate = parent::onUpdate($currentTick);

		if($this->isCollided){
			$this->sound();
			$this->delete();
			$hasUpdate = true;
		}

		$this->timings->stopTiming();

		return $hasUpdate;
	}
	
	public function delete(){
		$this->despawnFromAll();
		$this->setHealth(0);
		$this->kill();
	}

	public function sound(){
		return $this->level->addSound(new \pocketmine\level\sound\ExpPickupSound($this));
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->type = RainbowSand::NETWORK_ID;
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		
		$player->dataPacket($pk);
		
		$sound = new \pocketmine\level\sound\ButtonClickSound($this);
        	$sound->x = $this->x;
        	$sound->y = $this->y;
        	$sound->z = $this->z;
		
		$this->level->addSound($sound);
		
		parent::spawnTo($player);
	}
}
?>
