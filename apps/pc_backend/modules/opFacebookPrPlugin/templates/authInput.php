<?php slot('title', __('Facebook Authorization - step 1')); ?>


<?php if($isToken): ?><p><?php echo __('Already authorized.'); ?></p><?php endif; ?>

<?php echo button_to(__('Click here to authorize now'), $sf_data->getRaw('fbUrl')); ?>