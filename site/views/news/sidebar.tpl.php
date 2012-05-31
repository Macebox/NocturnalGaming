<h3>News</h3>
<p class="smaller">News is updated by admins only. All admins can edit news. A user is able to comment a newspost, a prospect is limited to only watch.</p>
<h4>Categories:</h4>
<ul>
<? foreach($categories as $category):?>

<li><a href="<?=create_url("news/category/{$category}")?>"><?=$category?></a></li>

<? endforeach; ?>
</ul>

<h4>Tags:</h4>

<p class="smaller-text" style="padding-left: 10px; padding-right: 10px; width: 200px;">
<? foreach($tags as $tag):?>

<a href="<?=create_url("news/tag/{$tag}");?>"><?=$tag?></a>,

<? endforeach;?>
</p>