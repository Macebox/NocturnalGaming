<h2>Source:</h2>
<?
$dirs = explode('/',$filename);
echo '<a href="'.create_url('source').'">.</a>';
$path = '';
foreach($dirs as $dir)
{
	if (!preg_match('/[a-zA-Z0-9]+\.[a-zA-Z0-9]+/', $dir))
	{
		echo '/<a href="'.create_url('source/index/'.$path.$dir).'">'.$dir.'</a>';
	}
	else
	{
		echo '/<a href="'.create_url('source/view/'.$path.$dir).'">'.$dir.'</a>';
	}
	$path .= $dir.'/';
}
?>
<?=$file?>