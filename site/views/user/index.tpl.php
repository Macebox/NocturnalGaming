<h2>User Index</h2>

<p>

<?php if($is_authenticated): ?>

<a href='<?=create_url('user/profile')?>'>Profile</a>

<?php else: ?>

<a href='<?=create_url('user/login')?>'>Login </a>
<?php if($allow_create_user): ?>
<a href='<?=create_url('prospects/create')?>'>or create a new user</a>
<?php endif; ?>
<?php endif; ?>

</p>