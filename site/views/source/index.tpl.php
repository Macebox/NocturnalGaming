<h2>Files and directorys:</h2>
<?
$dirs = explode('/',$directory);
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
<div class="lineTop">
<? foreach($fd as $dir => $url): ?>
<a href="<?=$url?>"><?=$dir?></a><br>
<? endforeach; ?>
</div>