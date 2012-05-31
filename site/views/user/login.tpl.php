<?=$login_form;?>

<p>

<?php if($allow_create_user): ?>
<a href='<?=create_url('prospects/create')?>'>Create a new user</a>
<?php endif; ?>

</p>