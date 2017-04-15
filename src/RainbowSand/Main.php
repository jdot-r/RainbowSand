<?php
namespace RainbowSand;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\entity\Entity;
use pocketmine\nbt\tag\{CompoundTag, DoubleTag, ListTag, FloatTag};
use pocketmine\utils\Config;
use RainbowSand\RainbowSand;
use pocketmine\block\Block;

class Main extends PluginBase {
	
	const FILE = [
		"config.yml" => [
			"customBlock" => "false",
			"id" => 5,
			"damage" => 0,
			"maxY" => 1,
			"minY" => 1,
			"distanceX" => 1,
			"distanceZ" => 1,
			"x" => 1,
			"y" => 1,
			"z" => 1,
			"level" => "world",
			"time" => 1,
			"multipliedBy" => 15
		],
	];
	
	public static $customBlock;
	public static $maxY;
	public static $minY;
	public static $distanceX;
	public static $distanceY;
	public static $sx;
	public static $sy;
	public static $sz;
	public static $level;
	public static $time;
	public static $multiply;

	public function onEnable() 
	{
		if(!is_dir($this->getDataFolder())) mkdir($this->getDataFolder());
		foreach(array_keys(self::FILE) as $file) self::$updateFile($file);
		$config = yaml_parse_file($this->getDataFolder() . "config.yml");
		self::$maxY = $config["maxY"] ?? 1;
		self::$minY = $config["minY"] ?? 1;
		self::$distanceX = $config["distanceX"] ?? 1;
		self::$distanceZ = $config["distanceZ"] ?? 1;
		self::$sx = $config["x"] ?? 1;
		self::$sy = $config["y"] ?? 1;
		self::$sz = $config["z"] ?? 1;
		self::$level = $config["level"] ?? "world";
		self::$time = $config["time"] ?? 1;
		self::$multiply = $config["multipliedBy"] ?? 15;
		Entity::registerEntity(RainbowSand::class);
		$server = Server::getInstance();
		$spawn = $this->getServer()->getDefaultLevel();
		$server->getScheduler()->scheduleRepeatingTask(new SandTask($this), self::$time * self::$multiply);
        }
	
	private static function updateFile(string $file) 
	{
		if(isset(self::FILE[$file])){
			$data = [];
			if(is_file($path = $this->getDataFolder() . $file)){
				$data = yaml_parse_file($path);
			}
			foreach(self::FILE[$file] as $key => $value){
				if(!isset($data[$key])){
					$data[$key] = $value;
				}
			}
			yaml_emit_file($path, $data);
		}
	}

	public function makeSand() 
	{
		//$cfg = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
		$randX = null;
		$randZ = null;
		$server = $this->getServer();
	   	if(self::$level === $server->getDefaultLevel()->getSafeSpawn()) {
 	   		$n1 = self::$sx + self::$distanceX);
	   		$n2 = self::$sz + self::$distanceZ);
	   		if($n1 < self::$sx){
				$randX = mt_rand($n1, self::$sx);
				       }else{
				$randX = mt_rand(self::$sx, $n1);
			}

	   		if($n2 < self::$sz){
				$randZ = mt_rand($n2, self::$sz);
	   			}else{
				$randZ = mt_rand(self::$sz, $n2);
			}
			$randY = mt_rand(self::$sy + self::$minY, self::$sy + self::$maxY);
			if(self::$customBlock === "true"){
				$block = Entity::createEntity("Block", self::$level->getChunk($randX >> 4, $randZ >> 4), $nbt);
				$nbt = new CompoundTag("", [
					"Pos" => new ListTag("Pos", [
					new DoubleTag("", $randX),
             				new DoubleTag("", $randY),
             				new DoubleTag("", $randZ)
				]),
					"Motion" => new ListTag("Motion", [
             				new DoubleTag(""),
             				new DoubleTag(""),
             				new DoubleTag("")
           			]),
					"Rotation" => new ListTag("Rotation", [
             				new FloatTag(""),
             				new FloatTag("", 90)
           			]),
           				"TileID" => new IntTag("TileID", self::$id),
           				"Data" => new ByteTag("Data", self::$damage)
           				//"Owner" => new LongTag("Owner", $this),
     				]);
				/*//Save
				$this->entityBlock = $block;
				return $block;*/ //?
			}else{
				if(self::$customBlock === "false"){
					$nbt = new CompoundTag("", [
						"Pos" => new ListTag("Pos", [
						new DoubleTag("" , $randX),
						new DoubleTag("" , $randY),
						new DoubleTag("" , $randZ)
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
					$sand = Entity::createEntity("RainbowSand", self::$level->getChunk($randX >> 4, $randZ >> 4), $nbt);
					return $sand;
				}
			}
		}
	}

	public function set($a) 
	{
		$n = mt_rand(1,2);
		if($n === 1){
			return $a;
		}else{
			return -$a;
		}
	}
}
