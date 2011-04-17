<?php

class opFacebookPrPluginConfigForm extends BaseForm
{
  public function setup()
  {
    $this->setWidget('is_fb', new sfWidgetFormChoice(array('choices'=>array(0=>'off', 1=>'on'))));
    $this->setValidator('is_fb', new sfValidatorChoice(array('required'=>true, 'choices'=>array(0,1))));
    $fbs = array('target', 'appid', 'secret');
    foreach($fbs as $fb)
    {
      $this->setWidget('fb_'.$fb, new sfWidgetFormInputText(array(), array('size'=>50)));
      $this->setValidator('fb_'.$fb, new sfValidatorString(array('required'=>true, 'max_length'=>100)));
    }

    foreach($this as $key => $field)
    {
      $this->setDefault($key, $this->getDefaultValue($key));
    }
    
    $this->getWidgetSchema()->setNameFormat($this->getName().'[%s]');
    $this->getWidgetSchema()->getFormFormatter()->setTranslationCatalogue('form_fbprplugin');
  }
  
  protected function getDefaultValue($key)
  {
    return Doctrine::getTable('SnsConfig')->get($this->getName().'_'.$key);
  }
  
  public function save()
  {
    $table = Doctrine::getTable('SnsConfig');
    foreach($this->getValues() as $key => $value)
    {
      $table->set($this->getName().'_'.$key, $value);
    }
  }
  
  public function getName()
  {
    return 'opFacebookPrPlugin';
  }
}