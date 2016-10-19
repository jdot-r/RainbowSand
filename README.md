# RainbowSand
Randomly spawns custom falling sand in a designated position


TODO:

```php
$entity = Entity::createEntity("Block", $player->getLevel()->getChunk($player->x >> 4, $player->z >> 4), new CompoundTag("", [
           "Pos" => new ListTag("Pos", [
             new DoubleTag("", $player->x),
             new DoubleTag("", $player->y),
             new DoubleTag("", $player->z)
           ]),
           "Motion" => new ListTag("Motion", [
             new DoubleTag("", 0),
             new DoubleTag("", 0),
             new DoubleTag("", 0)
           ]),
           "Rotation" => new ListTag("Rotation", [
             new FloatTag("", 0),
             new FloatTag("", 0)
           ]),
           "TileID" => new IntTag("TileID", $id),
           "Data" => new ByteTag("Data", $damage),
           "Owner" => new LongTag("Owner", $player),
     ]));

     foreach($this->getServer()->getOnlinePlayers() as $for){
       $entity->spawnTo($for);
     }
```
