<?php

/**
 * The Gaming clan
 * 
 * @package NGamingCore
 */

class CMNGaming extends CObject implements IModule
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Manage($action=null)
	{
		switch($action)
		{
			case 'install':
			{
				if ($this->Init())
				{
					return array('success', 'Succesfully created');
				}
				else
				{
					return array('error', 'Unable to complete.');
				}
				break;
			}
			default:
				break;
		}
	}
	
	public function Init()
	{
		$this->database->RunQuery('
CREATE TABLE IF NOT EXISTS `stats` (
  `idUser` int(11) NOT NULL,
  `nick` varchar(30) NOT NULL,
  `rank` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `deaths` int(11) NOT NULL,
  UNIQUE KEY `idUser` (`idUser`),
  KEY `nick` (`nick`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;', true);

		$this->user->Create('Macebox','qwerty','Marcus','macebox@localhost');
		
		$id = $this->database->Get('user', array('id'), array('acronym' => 'Macebox'));
		$id = $id[0]['id'];
		
		$this->database->Insert('stats', array(
			'idUser'	=> $id,
			'nick'		=> 'F0X3N',
			'rank'		=> '1',
			'kills'		=> '1337',
			'deaths'	=> '0',
			)
		);
		return true;
	}
	
	public function ChangeStats($id, $nick, $rank, $kills, $deaths)
	{
		$array = array(
			'nick'		=> $nick,
			'rank'		=> $rank,
			'kills'		=> $kills,
			'deaths'	=> $deaths,
		);
		
		if ($this->database->Update('stats', $array, array('idUser' => $id)))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function AddStats($id, $nick, $rank, $kills, $deaths)
	{
		$array = array(
			'idUser'	=> $id,
			'nick'		=> $nick,
			'rank'		=> $rank,
			'kills'		=> $kills,
			'deaths'	=> $deaths,
		);
		
		if ($this->database->Insert('stats', $array))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function ReadAll($rank=null)
	{
		$eq = array();
		if ($rank!=null)
		{
			$eq['rank'] = $rank;
		}
		$eq['user.id'] = '#stats.idUser';
		
		return $this->database->Get('stats, user',array('stats.*', 'user.email', 'user.id'), $eq);
	}
	
	public function ReadStats($id)
	{
		$eq = array();
		$eq['stats.idUser'] = '#user.id';
		$eq['user.id'] = $id;
		
		if ($ret = $this->database->Get('stats, user',array('stats.*', 'user.email', 'user.id'), $eq))
		{
			$ret = $ret[0];
		}
		
		return $ret;
	}
	
	public function GetAllUsers()
	{
		$ret = array();
		$arr = $this->database->Get('user', array('acronym'));
		
		foreach($arr as $value)
		{
			$ret[] = $value['acronym'];
		}
		
		return $ret;
	}
}