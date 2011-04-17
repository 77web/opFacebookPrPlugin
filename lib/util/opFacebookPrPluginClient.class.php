<?php

class opFacebookPrPluginClient
{
  static protected $fb;
  static protected $token;
  static protected $target;
  
  protected static function setup()
  {
    $snsConfig = Doctrine::getTable('SnsConfig');
    $config = array();
    $config['appId'] = $snsConfig->get('opFacebookPrPlugin_fb_appid');
    $config['secret'] = $snsConfig->get('opFacebookPrPlugin_fb_secret');
    
    Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
    Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 2;
    
    self::$token = $snsConfig->get('opFacebookPrPlugin_fb_token');
    self::$target = $snsConfig->get('opFacebookPrPlugin_fb_target');
    if(self::$token!='' && self::$target!='')
    {
      self::$fb = new Facebook($config);
    }
  }
  
  public static function postWall($msg, $url, $img = null)
  {
    self::setup();
    if(self::$fb)
    {
      //$post = array();
      //$post['access_token'] = self::$token;
      //$post['name'] = $msg;
      //$post['link'] = $url;
      //if($img)
      //{
      //  $post['picture'] = $img;
      //}
      
      $res = false;
      try
      {
        //$res = self::$fb->api('/'.self::$target.'/feed/', 'POST', $post);
        
        //to post in page name, currently must call old api...
        $p = array();
        $p['method'] = 'stream.publish';
        $p['uid'] = self::$target;
        $p['message'] = $msg.' '.$url;
        
        $res = self::$fb->api($p);
      }
      catch(FacebookApiException $e)
      {
        if('test' == sfConfig::get('sf_environment'))
        {
          echo $e->getMessage();
        }
      }

      if($res)
      {
        return true;
      }
    }

    return false;
  }
}