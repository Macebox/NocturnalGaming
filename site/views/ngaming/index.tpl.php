<h2>NocturnalGaming</h2>
<h4>The killer clan</h4>

<? foreach($members as $member): ?>
<img src="<?='http://www.gravatar.com/avatar/' . md5(strtolower(trim($member['email']))) . '.jpg?' . "s=110";?>" />
<p><?=$member['nick']?> - <?echo "<a href='".create_url('ngaming/index/'.$member['rank'])."'>".$ranks[$member['rank']].'</a>';?><br>
<span class="smaller-text">
Kills: <?=$member['kills']?><br>
Deaths: <?=$member['deaths']?><br>
K/D: <?=($member['deaths']==0?'Inf':$member['kills']/$member['deaths'])?><br>
</span>
</p>
<? endforeach; ?>