<?php slot('title', __('Facebook application setting')); ?>

<p><?php echo __('Please create empty facebook application and take down your app id and app secret'); ?></p>

<form action="<?php echo url_for('op_facebook_pr_plugin_config'); ?>" method="post">
<table class="form">
<?php echo $form; ?>
</table>

<input type="submit" value="<?php echo __('Save'); ?>" />
</form>

<?php if($isPreAuth): ?><?php echo button_to(__('Authorize now'), 'op_facebook_pr_plugin_auth'); ?><?php endif; ?>