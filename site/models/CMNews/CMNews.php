<?php

class CMNews extends CObject implements IModule, ArrayAccess
{
	public function __construct($id=null)
	{
		parent::__construct();
		if (isset($id))
		{
			$this->LoadById($id);
		}
		else
		{
			$this->data = array();
		}
	}
	
	public function offsetSet($offset, $value){if (is_null($offset)){$this->data[] = $value;}else{$this->data[$offset] = $value;}}
	public function offsetExists($offset) {return isset($this->data[$offset]);}
	public function offsetUnset($offset) {unset($this->data[$offset]);}
	public function offsetGet($offset) {return isset($this->data[$offset])?$this->data[$offset]:null;}
	
	public function Manage($action=null)
	{
		switch($action)
		{
			case 'install':
				{
					if ($this->Init())
					{
						return array('success', 'Succesfully created the database table.');
					}
					else
					{
						return array('error', 'Unable to create the database table.');
					}
				} break;
		}
	}
	
	public function Init()
	{
		$ret = $this->database->RunQuery("CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `category` text NOT NULL,
  `tag` text NOT NULL,
  `idUser` int(11) NOT NULL,
  `bbcode` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1", true) ;
	
	$tmp = $this->database->RunQuery('CREATE TABLE IF NOT EXISTS `newscomment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idNews` int(11) NOT NULL,
  `content` text NOT NULL,
  `idUser` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idNews` (`idNews`,`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;', true);

	$tmp2 = $this->database->RunQuery("INSERT INTO `news` (`id`, `title`, `content`, `category`, `tag`, `idUser`, `bbcode`, `created`, `deleted`) VALUES
(1, 'Test', '[b]Yes we are using bbcode[/b] [i]HEJ :D[/i]            as sadasd ad aas ads adsa d dsa ads ads ads ads adsads  ads ads ads ads ads\r\n[code]<?php\r\n\$abc = 123;\r\n\$bcd = ''123'';[/code]', 'test', 'test,first,', 1, 1, '2012-05-28 22:10:05', NULL),
(2, 'Fillings', 'Yes this seems unnecessary, but i will do it anyway. Filling to show that news can be shown on several pages.', 'General', 'test, general', 1, 0, '2012-05-31 01:20:40', NULL),
(3, 'Updating', '[b]This is[/b] [i]to show[/i] [u]that all[/u] [quote]kinds of[/quote] [code]\$bbcode = 1;[/code] [size=40]works[/size] [color=blue], cool huh?[/color]', 'General', 'general, test', 1, 1, '2012-05-31 01:24:32', NULL),
(4, 'Another filler, just for fun', 'I hope you dont mind..', 'General', 'test', 1, 0, '2012-05-31 01:33:32', NULL),
(5, 'Another news', 'This is not good, is it?', 'General', 'test', 1, 0, '2012-05-31 01:36:20', NULL),
(6, 'The Killer Clan', 'The killer clan is a clan that kills. End of story.', 'NGaming', 'general, ngaming', 1, 0, '2012-05-31 01:43:49', NULL);",true);

	$tmp3 = $this->database->RunQuery("INSERT INTO `newscomment` (`id`, `idNews`, `content`, `idUser`, `created`) VALUES
(1, 1, 'Test comment asdsad testing line length etc... is this working?', 1, '2012-05-29 23:28:00'),
(2, 1, 'Testing again, this time to see if enough spacing...', 1, '2012-05-29 23:32:00');", true);
	
	return ($ret && $tmp && tmp2 && $tmp3);
	}
	
	public function ReadAll($category=null, $tag=null, $eqs=null, $page=1, $newsPerPage=10)
	{
		$eq = array('user.id' => '#news.idUser');
		if (!empty($category))
		{
			$eq['category'] = $category;
		}
		if (!empty($tag))
		{
			$eq['tag'] = '%'.$tag.'%';
		}
		
		if (isset($eqs) && is_array($eqs))
		{
			foreach($eqs as $key => $value)
			{
				$eq[$key] = $value;
			}
		}
		
		$limit = null;
		if (isset($page))
		{
			$limit = (($page-1)*$newsPerPage).", ".$newsPerPage;
		}
		
		$ret = $this->database->Get('news, user', array('news.*', 'user.acronym'), $eq, array('id'), false, false, $limit);
		
		$acronymArray = array();
      
		for ($i=0;$i<count($ret); $i++)
		{
			if ($ret[$i]['bbcode'])
			{
				$ret[$i]['content'] = CMContent::Filter($ret[$i]['content'], 'bbcode');
			}
			else
			{
				$ret[$i]['content'] = CMContent::Filter($ret[$i]['content'], 'plaintext');
			}
		}
		
		return $ret;
	}
	
	public function getNewsPages($page, $eq=array(), $newsPerPage=10)
	{
		$arr = $this->database->Get('news', array('count(*)'), $eq);
		
		$pages = (int)($arr[0]['count(*)']/$newsPerPage);
		
		if (($arr[0]['count(*)'] % $newsPerPage)!=0)
		{
			$pages += 1;
		}
		
		$ret = array();
		
		$ret[] = 1;
		
		$start = $page-2;
		
		$start = ($page<3)?3:$start;
		$start = ($page>($pages-3))?$pages-3:$start;
		
		$end = $start+4;
		
		for($i=$start;$i<=$end; $i++)
		{
			if ($i>0 && $i<=$pages)
			{
				$ret[] = $i;
			}
		}
		
		$ret[] = $pages;
		
		$ret = array_unique($ret);
		
		sort($ret);
		
		return $ret;
	}
	
	public function getNews($newsId)
	{
		if (($ret = $this->database->Get('news', array(), array('id' => $newsId))))
		{
			$ret = $ret[0];
			$id = $ret['idUser'];
			$acronym = $this->database->Get('user',array('acronym'),array('id'=>$id));
			$ret['userAcronym'] = $acronym[0]['acronym'];
			
			if ($ret['bbcode'])
			{
				$ret['content'] = CMContent::Filter($ret['content'], 'bbcode');
			}
			else
			{
				$ret['content'] = CMContent::Filter($ret['content'], 'plaintext');
			}
		}
		
		return $ret;
	}
	
	public function getComments($newsId)
	{
		$ret = $this->database->Get('newscomment', array(), array('idNews' => $newsId));
		
		$acronymArray = array();
      
		for ($i=0;$i<count($ret); $i++)
		{
			$id = $ret[$i]['idUser'];
			if (!isset($acronymArray[$id]))
			{
				$acronym = $this->database->Get('user',array('acronym'),array('id'=>$id));
				$acronymArray[$id] = $acronym[0]['acronym'];
			}
			$ret[$i]['userAcronym'] = $acronymArray[$id];
			$ret[$i]['content'] = CMContent::Filter($ret[$i]['content'], 'plaintext');
		}
		
		return $ret;
	}
	
	public function GetCategories()
	{
		$result = $this->database->Get('news', array('category'), array(), array(), false, true);
		
		$ret = array();
		
		foreach($result as $res)
		{
			$ret[] = $res['category'];
		}
		
		sort($ret);
		
		return $ret;
	}
	
	public function GetTags()
	{
		$result = $this->database->Get('news', array('tag'), array(), array(), false, true);
		
		$ret = array();
		
		foreach($result as $res)
		{
			$res = $res['tag'];
			if (strpos($res, ','))
			{
				$arr = explode(',', $res);
				foreach($arr as $val)
				{
					if (!empty($val))
					{
						$val = trim($val);
						$ret[] = $val;
					}
				}
			}
			else if (!empty($res))
			{
				$res = trim($res);
				$ret[] = $res;
			}
		}
		
		$ret = array_unique($ret);
		
		sort($ret);
		
		return $ret;
	}
	
	public function Save()
	{
		$userId = $this->user;
		if (!$userId['id'])
		{
			return;
		}
		$userId = $userId['id'];
		$msg = null;
		$succ = false;
		if ($this['id'])
		{
			$updateArray = array(
				'title'		=> $this['title'],
				'content'	=> $this['content'],
				'category'	=> $this['category'],
				'tag'		=> $this['tag'],
				'bbcode'	=> $this['bbcode'],
				'deleted'	=> null,
			);
			
			if ($this->database->Update('news',$updateArray,array('id'=>$this['id'])))
			{
				$succ = true;
			}
			$msg = 'update';
		}
		else
		{
			$insertArray = array(
				'title'		=> $this['title'],
				'content'	=> $this['content'],
				'idUser'	=> $userId,
				'category'	=> $this['category'],
				'tag'		=> $this['tag'],
				'bbcode'	=> $this['bbcode'],
				'created'	=> date('o-m-d H:i:s'),
			);
			
			if ($this->database->Insert('news', $insertArray))
			{
				$succ = true;
				$this['id'] = $this->database->getLastId();
			}
			
			$msg = 'create';
		}
		if ($succ)
		{
			$this->session->AddMessage('success', "Successfully {$msg} news '{$this['title']}'.");
		}
		else
		{
			$this->session->AddMessage('error', "Failed to {$msg} news '{$this['title']}'.");
		}
	}
	
	public function Remove()
	{
		return $this->database->Update('news',array('deleted'=>date('o-m-d H:i:s')),array('id'=>$this['id']));
	}
	
	public function LoadById($id)
	{
		$res = $this->database->Get('news','',array('id'=>$id));
		if (empty($res))
		{
			$this->session->AddMessage('error', "Failed to load news '{$id}'.");
		}
		else
		{
			$this->data = $res[0];
		}
		
		return true;
	}
}