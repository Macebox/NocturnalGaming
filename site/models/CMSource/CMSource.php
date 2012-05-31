<?php

class CMSource extends CObject
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function ReadDir($path)
	{
		$ret = array();
		$handle = opendir(MVC_INSTALL_PATH.'/'.$path);
		
		while(false !== ($entry = readdir($handle)))
		{
			if ($entry[0]!='.')
			{
				if (is_dir($path.$entry))
				{
					$ret[$entry] = $this->request->CreateUrl('source/index/'.$path.$entry);
				}
				else
				{
					$ret[$entry] = $this->request->CreateUrl('source/view/'.$path.$entry);
				}
			}
		}
		
		closedir($handle);
		
		return $ret;
	}
	
	public function ReadFile($path)
	{
		$handle = fopen(MVC_INSTALL_PATH.'/'.$path, "r");
		
		$contents = fread($handle, filesize($path));
		
		fclose($handle);
		
		$search = array(
			'/\'host\'(.*)\=\>(.*)\'(.*)\',/',
			'/\'user\'(.*)\=\>(.*)\'(.*)\',/',
			'/\'password\'(.*)\=\>(.*)\'(.*)\',/',
			'/\'db\'(.*)\=\> \'(.*)\',/',
			'/\'dsn\'(.*)\=\>(.*)\'(.*)\',/',
			);
		$replace = array(
			"'host' => '****',",
			"'user' => '****',",
			"'password' => '****',",
			"'db' => '****',",
			"'dsn' => '****',",
			);
		
		$contents = preg_replace($search, $replace, $contents);
		
		return nl2br(code2html(htmlEnt($contents)));
	}
}