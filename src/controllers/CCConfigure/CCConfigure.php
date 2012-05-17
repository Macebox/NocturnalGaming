<?php/***** @package NocturnalCore*/class CCConfigure extends CObject implements IController{	public function __construct()	{		parent::__construct();	}		public function Index()	{		if ($this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym']) || !$this->config['installed'])		{			$this->views->SetTitle('Configuration Index');			$this->views->AddView('configure/index.tpl.php',array(),'primary');			$this->views->AddView('configure/sidebar.tpl.php',array(),'sidebar');		}		else		{			$this->views->SetTitle('Error');			$this->views->AddView('configure/unauthorized.tpl.php',array(),'primary');		}	}		public function Install()	{		if ($this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym']) || !$this->config['installed'])		{			$this->views->SetTitle('Install the modules');			$this->views->AddView('configure/install.tpl.php',array(),'primary');			$this->views->AddView('configure/sidebar.tpl.php',array(),'sidebar');		}		else		{			$this->views->SetTitle('Error');			$this->views->AddView('configure/unauthorized.tpl.php',array(),'primary');		}	}		public function Configure()	{		if ($this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym']) || !$this->config['installed'])		{			$this->views->SetTitle('Configure');			$this->views->AddView('configure/configure.tpl.php',array('form'=>$this->getConfigurationForm()->GetHTML()),'primary');			$this->views->AddView('configure/sidebar.tpl.php',array(),'sidebar');		}		else		{			$this->views->SetTitle('Error');			$this->views->AddView('configure/unauthorized.tpl.php',array(),'primary');		}	}		public function DoConfigure($form)	{	}		private function getConfigurationForm()	{		$form = new CConfigureForm();				/*---------------------------- Site ------------------------------------*/		$form->AddElement(new CFormElementHeading('Site'));		$form->AddElement(new CFormElementText('Heading',	array('value' => $this->config['theme']['data']['header'])));		$form->AddElement(new CFormElementText('Slogan',	array('value' => $this->config['theme']['data']['slogan'])));		$form->AddElement(new CFormElementText('Footer',	array('value' => htmlent($this->config['theme']['data']['footer']))));				/*---------------------------- Theme ------------------------------------*/				$form->AddElement(new CFormElementHeading('Theme'));				$form->AddElement(new CFormElementSelect('theme', array('options' => $this->getThemes())));				/*---------------------------- Navigation ------------------------------------*/				$form->AddElement(new CFormElementHeading('Navigation(Menus)'));		for ($i=0; $i<3; $i++)		{			$form->AddElement(new CFormElementSelect('Nav-'.($i*3+1), array('options' => $this->getMenus($i*3))));			$form->AddElement(new CFormElementSelect('Nav-'.($i*3+2), array('options' => $this->getMenus($i*3+1))));			$form->AddElement(new CFormElementSelect('Nav-'.($i*3+3), array('options' => $this->getMenus($i*3+2))));			$form->AddElement(new CFormElementText('Nav-'.($i*3+1).'-Title'));			$form->AddElement(new CFormElementText('Nav-'.($i*3+2).'-Title'));			$form->AddElement(new CFormElementText('Nav-'.($i*3+3).'-Title'));		}				/*---------------------------- Database ------------------------------------*/				$form->AddElement(new CFormElementHeading('Database'));				$form->AddElement(new CFormElementCheckbox('active', 		array('checked' => $this->config['database']['active'], 'label' => 'Active?')));		$form->AddElement(new CFormElementText('dbdsn',				array('value'	=> $this->config['database']['dsn'], 'label' => 'DSN:')));		$form->AddElement(new CFormElementText('dbhost',			array('value'	=> $this->config['database']['host'], 'label' => 'Host:')));		$form->AddElement(new CFormElementText('dbuser',			array('value'	=> $this->config['database']['user'], 'label' => 'User:')));		$form->AddElement(new CFormElementCheckbox('usedbpw',		array('checked'	=> !empty($this->config['database']['password']), 'label' => 'Use password?')));		$form->AddElement(new CFormElementPassword('dbpassword',	array('label'	=> '*Password: ')));		$form->AddElement(new CFormElementPassword('dbpassword2',	array('label'	=> '*Password again: ')));				/*---------------------------- Debug ------------------------------------*/				$form->AddElement(new CFormElementHeading('Debug'));				$form->AddElement(new CFormElementCheckboxGroup('debug', array('options' => $this->getDebugSettings(), 'columns' => 4)));						/*---------------------------- Other ------------------------------------*/				$form->AddElement(new CFormElementHeading('Other'));				$form->AddElement(new CFormElementSelect('timezone', array('options' => $this->getTimezones())));				$form->AddElement(new CFormElementSubmit('Save', array('callback' => array($this, 'DoConfigure'))));				return $form;	}		private function getMenus($id)	{		$ret = array();				$nav = $this->getNavbars();				if ($id>=count($nav))		{			$ret[] = "None";		}				foreach($this->getControllers() as $value)		{			if ($id<count($nav) && $value == $nav[$id])			{				$ret[] = array('value'=>$value,'selected'=>'selected');			}			else			{				$ret[] = $value;			}		}				return $ret;	}		private function getDebugSettings()	{		$ret = array();				$ret[]		= array('checked' => $this->config['debugEnabled'],				'value'	=> 'debug',		'label' => 'Debug?');		$ret[]		= array('checked' => $this->config['debug']['mvc'],				'value' => 'debugMVC',	'label' => 'Show MVC-dump?');		$ret[]		= array('checked' => $this->config['debug']['db-num-queries'],	'value' => 'debugNQ',	'label' => 'Show number of database queries?');		$ret[]		= array('checked' => $this->config['debug']['db-queries'],		'value' => 'debugQ',	'label' => 'Show database queries?');				return $ret;	}		private function getNavbars()	{		$ret = array();				if (isset($this->config['navbar']))		{			foreach($this->config['navbar'] as $key => $array)			{				if (isset($this->config['controllers'][$array['url']]))				{					$ret[] = $this->config['controllers'][$array['url']]['class'];				}			}		}				return $ret;	}		private function getThemes()	{		$ret = array();				if (isset($this->config['theme']['themes']))		{			foreach($this->config['theme']['themes'] as $themeName => $theme)			{				if ($this->config['theme']['path']==$theme['path'])				{					$ret[] = array('value' => $themeName, 'selected' => 'selected');				}				else				{					$ret[] = $themeName;				}			}		}				return $ret;	}		private function getControllers()	{		$ret = array();		$tmp = new CMModules();				foreach($tmp->ReadAndAnalyse() as $key => $array)		{			if ($array['isController'])			{				$ret[] = $key;			}		}				return $ret;	}		private function getTimezones()	{		$ret = array();		$regions = array(			'Africa' => DateTimeZone::AFRICA,			'America' => DateTimeZone::AMERICA,			'Antarctica' => DateTimeZone::ANTARCTICA,			'Asia' => DateTimeZone::ASIA,			'Atlantic' => DateTimeZone::ATLANTIC,			'Europe' => DateTimeZone::EUROPE,			'Indian' => DateTimeZone::INDIAN,			'Pacific' => DateTimeZone::PACIFIC		);		foreach ($regions as $name => $mask) {			foreach(DateTimeZone::listIdentifiers($mask) as $tz)			{				if (isset($this->config['timezone']) && $tz==$this->config['timezone'])				{					$ret[] = array('value'=>$tz, 'selected'=>'selected');				}				else					$ret[] = $tz;			}		}				return $ret;	}}