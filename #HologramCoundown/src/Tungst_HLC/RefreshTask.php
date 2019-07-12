<?php
namespace Tungst_HLC;
use pocketmine\scheduler\Task;


use pocketmine\math\Vector3;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
class RefreshTask extends Task implements Listener{
    /** @var Loader */
    private $main;
    public $maxtop; 
	public $floatingtext = []; 
	public $worldname = [];
    public $title = "§e-§6= CountDown§6=§e-";
    public $text = 30; //second
	public $isLoad = false;
	public $isCall = false;
	
	public function onJoin(PlayerJoinEvent $e){
	  if(!$this->isLoad){
		  $this->isCall = true;
		 
		  foreach($this->main->getConfig()->get("location") as $cm){
		     $a = new FloatingTextParticle(new Vector3($cm["x"],$cm["y"],$cm["z"]),$this->text,$this->title); 	
		     $this->main->getServer()->loadLevel($cm["level"]);
		     array_push($this->floatingtext,$a);
		     array_push($this->worldname,$cm["level"]);
		     $this->main->getServer()->getLevelByName($cm["level"])->addParticle($a); //work	
		  }
		  $this->isLoad = true;
	  }
	}
    public function __construct(Main $main){    
	
        $this->main = $main;
		$this->maxtop = $this->main->getConfig()->get("maxtop");
		
    }
    public function onRun($currentTick)
    {
		if($this->text <= 0){$this->text = 30;}
		$this->text--;
		if(!$this->isCall){return;}	
        if(count($this->floatingtext) == count($this->main->getConfig()->get("location"))){
			for($i = 0;$i<count($this->floatingtext);$i++){
				$this->floatingtext[$i]->setText($this->text);
			    $this->main->getServer()->getLevelByName($this->worldname[$i])->addParticle($this->floatingtext[$i]);
			}
		}else{
			for($i = 0;$i<count($this->floatingtext);$i++){
				$this->floatingtext[$i]->setInvisible();
			    $this->main->getServer()->getLevelByName($this->worldname[$i])->addParticle($this->floatingtext[$i]);
			}
			$this->floatingtext = [];
			$this->worldname = [];
			foreach($this->main->getConfig()->get("location") as $cm){
		       $a = new FloatingTextParticle(new Vector3($cm["x"],$cm["y"],$cm["z"]),$this->text,$this->title); 	
		       //$this->main->getServer()->loadLevel($cm["level"]);
		       array_push($this->floatingtext,$a);
		       array_push($this->worldname,$cm["level"]);
		       $this->main->getServer()->getLevelByName($cm["level"])->addParticle($a); //work	
		  }
		}
    }
}
