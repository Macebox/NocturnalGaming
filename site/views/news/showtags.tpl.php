<h2>Taglist:</h2>
<p class="smaller-text">
<? foreach($tags as $tag):?>

<a href="<?=create_url("news/tag/{$tag}");?>"><?=$tag?></a>,

<? endforeach;?>
</p>