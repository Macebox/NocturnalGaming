<?phpclass CCGuestbook extends CObject implements IController{	private $pageTitle = 'Nocturnal Guestbook';	private $guestbookModel;   /**   * Constructor   */	public function __construct()	{		parent::__construct();		$this->guestbookModel = new CMGuestbook();	}   /**   * Implementing interface IController. All controllers must have an index action.   */	public function Index()	{		$this->views->SetTitle($this->pageTitle);				$form = new CForm(array('action' => $this->request->CreateUrl('guestbook/Add')),		array(			'Text'		=> new CFormElementTextArea('newEntry', array('label'=>'Inl�gg:', 'class'=>'guestbookText')),			'Author'	=> new CFormElementText('author'),			'Send'		=> new CFormElementSubmit('doAdd', array('value'=>'Add comment')),			'Clear'		=> new CFormElementSubmit('doClear', array('value'=>'Clear all'))			)		);				$this->views->AddView('Guestbook/index.tpl.php', array(			'entries'			=> $this->guestbookModel->ReadAll(),			'GuestbookForm'		=> $form->GetHTML(),			),		'primary'		);	}		public function Add()	{		if (isset($_POST['doAdd']))		{			$entry = strip_tags($_POST['newEntry']);			$author = $_POST['author'];			$this->guestbookModel->Add(array(				'text'		=> $entry,				'author'	=> $author,				)			);					}		else if (isset($_POST['doClear']))		{			$this->guestbookModel->DeleteAll();		}		$this->RedirectToController();	} } 