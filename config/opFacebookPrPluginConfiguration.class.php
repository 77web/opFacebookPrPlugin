<?php

class opFacebookPrPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    //diary post
    $this->dispatcher->connect('op_action.post_execute_diary_create', array($this, 'listenToDiaryCreate'));
    
    //topic post
    $this->dispatcher->connect('op_action.post_execute_communityTopic_create', array($this, 'listenToCommunityTopicCreate'));
    
    //event post
    $this->dispatcher->connect('op_action.post_execute_communityEvent_create', array($this, 'listenToCommunityEventCreate'));
    
    //community create
    $this->dispatcher->connect('op_action.post_execute_community_edit', array($this, 'listenToCommunityEdit'));
    
  }
  
  public function listenToDiaryCreate($arguments)
  {
    $form = $arguments['actionInstance']->getVar("form");
    if($form && $form->getObject()->getId())
    {
      $diary = $form->getObject();
      if(1 == $diary->getIsOpen())
      {
        $params = array();
        $params['%name%'] = $diary->getTitle();
        $params['%member%'] = $diary->getMember()->getName();
        $this->publishFacebook('diary', $params, '@diary_show?id='.$diary->getId());
      }
    }
  }
  
  public function listenToCommunityTopicCreate($arguments)
  {
    $form = $arguments['actionInstance']->getVar("form");
    if($form && $form->getObject()->getId())
    {
      $topic = $form->getObject();
      if('public' == $topic->getCommunity()->getConfig('public_flag'))
      {
        $params = array();
        $params['%name%'] = $topic->getName();
        $params['%community%'] = $topic->getCommunity()->getName();
        $params['%member%'] = $topic->getMember()->getName();
        $this->publishFacebook('topic', $params, '@communityTopic_show?id='.$topic->getId());
      }
    }
  }
  
  public function listenToCommunityEventCreate($arguments)
  {
    $form = $arguments['actionInstance']->getVar("form");
    if($form && $form->getObject()->getId())
    {
      $event = $form->getObject();
      if('public' == $event->getCommunity()->getConfig('public_flag'))
      {
        $params = array();
        $params['%name%'] = $event->getName();
        $params['%community%'] = $event->getCommunity()->getName();
        $params['%member%'] = $event->getMember()->getName();
        $this->publishFacebook('event', $params, '@communityEvent_show?id='.$event->getId());
      }
    }
  }
  
  public function listenToCommunityEdit($arguments)
  {
    //pending...
  }
  
  public function publishFacebook($type, $params, $urlString)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url', 'I18N'));
    $template = sfConfig::get('app_facebook_wallmessage_'.$type, '');
    if('' != $template)
    {
      $msg = __($template, $params, 'facebook_wall_messages');
      $url = url_for($urlString, true);
      opFacebookPrPluginClient::postWall($msg, $url);
    }
  }
  

}