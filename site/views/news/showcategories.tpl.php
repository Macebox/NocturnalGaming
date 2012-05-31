<h2>All categories:</h2>
<ul>
<? foreach($categories as $category):?>

<li><a href="<?=create_url("news/category/{$category}")?>"><?=$category?></a></li>

<? endforeach; ?>
</ul>