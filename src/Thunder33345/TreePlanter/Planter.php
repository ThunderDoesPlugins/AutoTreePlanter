<?php
/** Created By Thunder33345 **/

namespace Thunder33345\TreePlanter;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Planter extends PluginBase implements Listener
{
	public function onLoad()
	{

	}

	public function onEnable()
	{
		$sec =  $this->getConfig()->get('seconds',30);
		$this->getLogger()->info('Planting task scheduled at interval of '.$sec." seconds");
		$this->getScheduler()->scheduleRepeatingTask(new PlanterTask($this), 20 *$sec);
	}

	public function onDisable()
	{

	}

}