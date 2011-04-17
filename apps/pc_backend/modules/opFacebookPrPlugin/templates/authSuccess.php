<div id="fb-root"></div>
<script src="http://connect.facebook.net/<?php echo $sf_user->getCulture(); ?>/all.js"></script>
<script>
  FB.init({
    appId  : '<?php echo $appId; ?>',
    status : false,
    cookie : false,
    xfbml  : false
  });
  window.onload = function()
  {
    FB.login(function(response){
      if(response.session)
      {
        if(typeof(response.session.access_token)!='undefined')
        {
          window.location.href = '<?php echo url_for('@op_facebook_pr_plugin_auth?complete=1'); ?>';
        }
      }
    }, {perms: 'publish_stream', enable_profile_selector: 1, profile_selector_ids: '<?php echo $fbTarget; ?>' });
  }
</script>

<?php slot('title', __('Facebook Authorization')); ?>


<p><?php echo __('Please allow popup if not popped.'); ?></p>
