<?php
declare(strict_types=1);
/* Made By Thunder33345 */

namespace Thunder33345\TreePlanter;

use pocketmine\block\Block;
use pocketmine\block\Sapling;
use pocketmine\entity\object\ItemEntity;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\scheduler\Task;

class PlanterTask extends Task
{
	private $loader, $server;

	public function __construct(Planter $loader)
	{
		$this->loader = $loader;
		$this->server = $loader->getServer();
	}

	public function onRun($currentTick)
	{
		foreach($this->server->getLevels() as $level){
			foreach($level->getEntities() as $entity){
				if(!$entity instanceof ItemEntity) continue;
				//Get all level and foreach all entities

				$item = $entity->getItem();
				//only allow saplings
				if($item->getId() !== Item::SAPLING) continue;

				$ground = $level->getBlockAt($entity->getFloorX(), $entity->getFloorY() - 1, $entity->getFloorZ());
				$above = $level->getBlockAt($entity->getFloorX(), $entity->getFloorY(), $entity->getFloorZ());

				if($ground->getId() !== Block::DIRT AND $ground->getId() !== Block::GRASS) continue;
				if($above->getId() !== Block::AIR) continue;

				$sapling = new Sapling($item->getDamage());
				$level->setBlock($above->asPosition(), $sapling, true, true);
				$level->broadcastLevelSoundEvent($above, LevelSoundEventPacket::SOUND_PLACE, $sapling->getRuntimeId());//todo find a better way

				if($entity->getItem()->getCount() > 1) $entity->getItem()->setCount($entity->getItem()->getCount() - 1);
				else $entity->flagForDespawn();

			}
		}
	}
}