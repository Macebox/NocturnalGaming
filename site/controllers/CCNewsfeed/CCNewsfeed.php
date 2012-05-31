<?php

/**
 *
 *
 */
 
class CCNewsfeed extends CObject implements IController
{
	private $npp = 10;
	
	public function __construct()
	{
		parent::__construct();
		$model = new CMNews();
		
		$this->views->AddView('news/feat-first.tpl.php', array('user'=>$this->user['isAuthenticated']), 'featured-first');
		
		$this->views->AddView('news/feat-middle.tpl.php', array(), 'featured-middle');
		
		if ($this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym']))
		{
			$this->views->AddView('news/feat-last.tpl.php', array('admin' => 1), 'featured-last');
		}
		
		$tags = $model->GetTags();
		
		if (count($tags)>30)
		{
			$tags = array_slice($tags, 0, 30);
		}
		
		$cat = $model->GetCategories();
		
		if (count($cat)>20)
		{
			$cat = array_slice($cat, 0, 20);
		}
		
		$this->views->AddView('news/sidebar.tpl.php', array('categories' => $cat, 'tags' => $tags), 'sidebar');
		
		if ($this->session->__get('newsPerPage')!=null)
		{
			$this->npp = $this->session->__get('newsPerPage');
		}
	}
	
	public function SetNewsPerPage($npp)
	{
		$this->session->__set('newsPerPage', $npp);
		$this->RedirectToController('index');
	}
	
	public function Index($page=1)
	{
		$model = new CMNews();
		$this->views->SetTitle('Newsfeed');
		$this->views->AddView('news/index.tpl.php', array(
			'news'		=> $model->ReadAll(null,null, array('deleted'=>null),$page, $this->npp),
			'head'		=> 'Newsfeed',
			'admin'		=> $this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym']),
			'user'		=> $this->user->GetAcronym(),
			'page'		=> $page,
			'pages'		=> $model->getNewsPages($page, array(), $this->npp),
			'method'	=> 'index',
			),
			'primary');
	}
	
	public function Show($show='categories')
	{
		$model = new CMNews();
		if ($show=='categories')
		{
			$this->views->SetTitle('News-categories');
			$this->views->AddView('news/showcategories.tpl.php', array('categories'	=> $model->GetCategories()), 'primary');
		}
		else if ($show=='tags')
		{
			$this->views->SetTitle('News-tags');
			$this->views->AddView('news/showtags.tpl.php', array('tags'	=> $model->GetTags()), 'primary');
		}
		else
		{
			$this->views->SetTitle('Error: 404');
			$this->views->AddString("<h2>Unable to show '{$show}'</h2>", 'primary');
		}
	}
	
	public function Tag($tag, $page=1)
	{
		$model = new CMNews();
		$this->views->SetTitle('News: '.$tag);
		$this->views->AddView('news/index.tpl.php', array(
			'news'		=> $model->ReadAll(null, $tag, array('deleted'=>null), $page, $this->npp),
			'head'		=> 'News tagged as: '.$tag,
			'admin'		=> $this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym']),
			'user'		=> $this->user->GetAcronym(),
			'page'		=> $page,
			'pages'		=> $model->getNewsPages($page, array('tag' => '%'.$tag.'%'), $this->npp),
			'method'	=> 'tag/'.$tag,
			),
			'primary'
		);
	}
	
	public function Category($cat, $page=1)
	{
		$model = new CMNews();
		
		$this->views->SetTitle('News: '.$cat);
		$this->views->AddView('news/index.tpl.php', array(
			'news'		=> $news = $model->ReadAll($cat, null, array('deleted'=>null), $page, $this->npp),
			'head'		=> 'News in category: '.$cat,
			'admin'		=> $this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym']),
			'user'		=> $this->user->GetAcronym(),
			'page'		=> $page,
			'pages'		=> $model->getNewsPages($page, array('category' => $cat), $this->npp),
			'method'	=> 'category/'.$cat,
			),
			'primary'
		);
	}
	
	public function Edit($id=null)
	{
		$news	= new CMNews($id);
		$save = isset($news['id'])? 'save' : 'create';
		$enableRemove = (!isset($news['deleted']));
		
		if ($id!=null)
		{
			$checked = $news['bbcode'];
		}
		else
		{
			$checked = false;
		}
		
		$form = new CForm(array('name'=>'editForm', 'action'=>$this->request->CreateUrl("news/edit/{$id}")),array(
			'title'		=> new CFormElementText('title', array(
				'value'		=> $news['title'],
				)),
			'content'		=> new CFormElementEditContent('content', array(
				'label'		=> 'News:',
				'value'		=> $news['content'],
				)),
			'category'	=> new CFormElementText('category', array(
				'value'		=> $news['category'],
				)),
			'tag'		=> new CFormElementText('tag', array(
				'value'		=> $news['tag'],
				)),
			'bbcode'	=> new CFormElementCheckbox('bbcode', array(
				'checked'	=> $checked,
				)),
			$save		=> new CFormElementSubmit($save, array(
				'callback'		=> array($this, 'DoSave'),
				'callback-args'	=> array($news),
				)),
			'remove'	=> (($save=='save')?new CFormElementSubmit('Remove', array('callback' => array($this, 'DoRemove'),'callback-args' => array($news),'disabled'=>($enableRemove?null:'disabled'))):null),
			'id'		=> new CFormElementHidden('id', array(
				'value'		=> $news['id'],
				)),
			)
		);
		
		$form->SetValidation('title', array('not_empty'));
		
		$status = $form->Check();
		if ($status === false)
		{
			$this->session->AddMessage('notice', 'The form could not be processed.');
			$this->RedirectToController("edit/{$id}");
		}
		else if ($status === true)
		{
			$this->RedirectToController("edit/{$id}");
		}
		
		$title = isset($id) ? 'Edit' : 'Create';
		
		$admin = $this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym'])?true:false;
		$this->views->SetTitle("{$title} news: {$id}");
		$this->views->AddView('news/edit.tpl.php', array(
			'user'		=> $this->user,
			'news'	=> $news,
			'form'		=> $form,
			'admin'		=> $admin,
			),
		'primary'
		);
	}
	
	public function DoSave($form, $news)
	{
		$news['id']    		= 		$form['id']			['value'];
		$news['title'] 		= 		$form['title']		['value'];
		$news['content']  	= 		$form['content']	['value'];
		$news['category'] 	= 		$form['category']	['value'];
		$news['tag']		= 		$form['tag']		['value'];
		$news['bbcode']		= isset($form['bbcode']		['value']);
		return $news->Save();
	}
	
	public function DoRemove($form, $news)
	{
		$news['id'] = $form['id']['value'];
		if ($news->Remove())
		{
			$this->session->AddMessage('success', 'Succesfully removed the news.');
			$this->RedirectToController('index');
		}
		else
		{
			$this->session->AddMessage('error', 'Unable to remove news.');
			$this->RedirectToController("edit/{$form[$id]}");
		}
	}
	
	public function Comment($id, $page=0)
	{
		$model = new CMNews();
		
		$form = new CForm();
		
		$form->AddElement(new CFormElementHidden('id', array('value' => $id)));
		$form->AddElement(new CFormElementTextArea('content', array('class' => 'commentBox', 'label' => 'Comment:')));
		$form->AddElement(new CFormElementSubmit('comment', array('callback' => array($this, 'DoComment'))));
		
		$form->Check();
		
		$this->views->SetTitle('News');
		$this->views->AddView('news/comment.tpl.php', array(
			'news'		=> $model->getNews($id),
			'comments'	=> $model->getComments($id),
			'admin'		=> $this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym']),
			'user'		=> $this->user->GetAcronym(),
			'form'		=> $form->GetHTML(),
			),
			'primary');
	}
	
	public function DoComment($form)
	{
		$model = new CMNews();
		return $model->AddComment($form['id'], $form['content']);
	}
}