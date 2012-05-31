<?php

/**
 * CCProspects
 *
 * @packageNGaming
 */

class CCProspects extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
		
		$this->views->AddView('prospects/featured-first.tpl.php', array(), 'featured-first');
		
		$this->views->AddView('prospects/sidebar.tpl.php',array(),'sidebar');
		
		if ($this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym']))
		{
			$this->views->AddView('prospects/featured-last.tpl.php', array(), 'featured-last');
		}
	}
	
	public function Index()
	{
		$model = new CMProspect();
		$this->views->SetTitle('All Prospects');
		$this->views->AddView('prospects/index.tpl.php',array(
			'prospects'		=> $model->ReadAll(),
			'admin'			=> $this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym']),
			),
			'primary');
	}
	
	public function Create()
	{
		$this->views->SetTitle('Register new prospect');
		$this->views->AddView('prospects/create.tpl.php', array(
			'form'		=> $this->getProspectCreateForm()->GetHTML(),
			),
			'primary');
	}
	
	public function DoCreateProspect($form)
	{
		$model = new CMProspect();
		
		$acronym			= $form['acronym']			['value'];
		$password			= $form['password']			['value'];
		$password_repeat	= $form['password-repeat']	['value'];
		$name				= $form['name']				['value'];
		$email				= $form['email']			['value'];
		
		if ($password==$password_repeat && $model->CreateProspect($acronym, $password, $name, $email))
		{
			$this->user->Login($acronym, $password);
			$this->RedirectToController('index');
		}
		else
		{
			$this->session->AddMessage('error', 'Unable to register as prospect.');
			$this->RedirectToController('create');
		}
	}
	
	public function getProspectCreateForm()
	{
		$form = new CForm();
		
		$form->AddElement(new CFormElementText('acronym', array()));
		$form->AddElement(new CFormElementPassword('password', array()));
		$form->AddElement(new CFormElementPassword('password-repeat', array('label' => 'Password again:')));
		$form->AddElement(new CFormElementText('name', array()));
		$form->AddElement(new CFormElementText('email', array()));
		$form->AddElement(new CFormElementSubmit('create', array(
			'callback' => array($this, 'DoCreateProspect'),
			)
		));
		
		$form->SetValidation('acronym',array('not_empty'));
		$form->SetValidation('password',array('not_empty'));
		$form->SetValidation('password-repeat', array('not_empty'));
		
		$form->Check();
		
		return $form;
	}
	
	public function Promote($id)
	{
		if ($this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym']))
		{
			$model = new CMProspect();
			if ($model->Promote($id))
			{
				$this->session->AddMessage('success', 'Promoted the prospect.');
				$this->RedirectToController('index');
			}
			else
			{
				$this->session->AddMessage('warning', 'Unable to promote prospect.');
				$this->RedirectToController('index');
			}
		}
		else
		{
			$this->views->SetTitle('Unauthorized');
		}
	}
	
	public function Remove($id)
	{
		if ($this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym']))
		{
			$model = new CMProspect();
			if ($model->Remove($id))
			{
				$this->session->AddMessage('success', 'Remove the prospect.');
				$this->RedirectToController('index');
			}
			else
			{
				$this->session->AddMessage('warning', 'Unable to remove prospect.');
				$this->RedirectToController('index');
			}
		}
		else
		{
			$this->views->SetTitle('Unauthorized');
		}
	}
}