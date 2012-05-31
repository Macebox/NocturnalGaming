<h2>All Prospects:</h2>

<table><tr><?$i=0;?>
<? foreach($prospects as $prospect): ?>
<td><?=$prospect['acronym']?><? if ($admin): ?> <?=create_button('prospects/promote/'.$prospect['id'],'<img src="'.create_url('site/data/approve.gif').'" />');?> <?=create_button('prospects/remove/'.$prospect['id'],'<img src="'.create_url('site/data/remove.gif').'" />');?><? endif; ?></td><?$i++; if ($i>2) {echo "</tr><tr>";$i=0;}?>
<? endforeach; ?>
</tr></table>
