<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opFacebookPrPlugin actions.
 *
 * @package    OpenPNE
 * @subpackage opFacebookPrPlugin
 * @author     Hiromi Hishida<info@77-web.com>
 * @version    0.9.0
 */
class opFacebookPrPluginActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('opFacebookPrPlugin', 'config');
  }
  
  public function executeConfig(sfWebRequest $request)
  {
    $this->form = new opFacebookPrPluginConfigForm();
    
    if($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getParameter($this->form->getName()));
      if($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->redirect('op_facebook_pr_plugin_config');
      }
    }
    
    $t = Doctrine::getTable('SnsConfig');
    $prefix = 'opFacebookPrPlugin';
    $this->appId = $t->get($prefix.'_fb_appid');
    $this->secret = $t->get($prefix.'_fb_secret');
    $this->isPreAuth = $this->appId && $this->secret;
  }
  
  public function executeAuth(sfWebRequest $request)
  {
    //if complete=1, set ok message and redirect to config form
    if(1 == $request->getParameter('complete'))
    {
      $this->getUser()->setFlash('notice', 'Authorized.');
      $this->redirect('op_facebook_pr_plugin_config');
      return;
    }
    
    
    $t = Doctrine::getTable('SnsConfig');
    $prefix = 'opFacebookPrPlugin';
    $this->appId = $t->get($prefix.'_fb_appid');
    $this->secret = $t->get($prefix.'_fb_secret');
    $this->fbTarget = $t->get($prefix.'_fb_target');
    $this->isToken = $t->get($prefix.'_fb_token')!='';

    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
    $this->callback = urlencode(url_for('opFacebookPrPlugin/auth', true));

    if(empty($this->appId) || empty($this->secret))
    {
      $this->getUser()->setFlash('notice', 'Your application id and application secret are required.');
      $this->redirect('op_facebook_pr_plugin_config');
    }
    
    $code = $request->getParameter('code');
    if(!$code)
    {
      $this->fbUrl = sprintf('https://www.facebook.com/dialog/oauth?scope=offline_access,manage_pages&client_id=%s', $this->appId);
      $this->fbUrl .= '&redirect_uri='.$this->callback;
      
      return sfView::INPUT;
    }
    
    $token_url = sprintf('https://graph.facebook.com/oauth/access_token?client_id=%s&client_secret=%s&code=%s', $this->appId, $this->secret, $code);
    $token_url .= '&redirect_uri='.$this->callback;
    $token = file_get_contents($token_url);

    if($token!='')
    {
      $token = str_replace('access_token=', '', $token);
      $t->set('opFacebookPrPlugin_fb_token', $token);
      return sfView::SUCCESS;
    }
    
    $this->getUser()->setFlash('notice', 'Authorization failed.');
    $this->redirect('op_facebook_pr_plugin_auth');
  }
  

}
