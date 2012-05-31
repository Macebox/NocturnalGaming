<? if(count($pages)<$page):?>

<h2>Page does not exist.</h2>

<? else:?>

<h2><?=$head?></h2>

<? /*Pages*/
if (count($pages)>0)
{
	echo '<p class="center">Page: ';
	
	for ($i=0; $i<count($pages); $i++)
	{
		if (($i!=0 && $pages[$i-1]!=($pages[$i]-1)))
		{
			echo "..";
		}
		if ($pages[$i]!=$page)
		{
			$url = create_url("news/{$method}/{$pages[$i]}");
			echo <<<EOD
 <a href="{$url}">{$pages[$i]}</a> 
EOD;
		}
		else
		{
			echo " {$pages[$i]} ";
		}
	}
	
	echo "</p>";
}
?>

<? foreach($news as $val):?>

<?=$val['title']?> <span class="smaller-text">by: <?=$val['acronym']?></span>
<div class="news">
<?=$val['content']?>
<p class="newsAuthor">
<span class="smaller-text">
Tags:

<?foreach(explode(',', $val['tag']) as $tag):?>

<? if(!empty($tag)): ?>
<a href="<?=create_url("news/tags/{$tag}");?>"><?=$tag?></a>
<? endif; ?>

<? endforeach; ?><br>
Category: <a href="<?=create_url("news/category/{$val['category']}")?>"><?=$val['category']?></a><br>
</span>
<span class="smaller-text">
@<?=$val['created']?><br>
<?=create_button("news/comment/{$val['id']}", '<img src="'.create_url('site/data/comment.gif').'" /> Comment')?> 
<? if($user==$val['acronym'] || $admin): ?>
<?=create_button("news/edit/{$val['id']}", '<img src="'.create_url('site/data/edit.gif').'" /> Edit')?> 
<? endif; ?>
</span>
</p>
</div>

<? endforeach; ?>

<? /*Pages*/
if (count($pages)>0)
{
	echo '<p class="center">Page: ';
	
	for ($i=0; $i<count($pages); $i++)
	{
		if (($i!=0 && $pages[$i-1]!=($pages[$i]-1)))
		{
			echo "..";
		}
		if ($pages[$i]!=$page)
		{
			$url = create_url("news/{$method}/{$pages[$i]}");
			echo <<<EOD
 <a href="{$url}">{$pages[$i]}</a> 
EOD;
		}
		else
		{
			echo " {$pages[$i]} ";
		}
	}
	
	echo "</p>";
}
?>

<? endif; ?>