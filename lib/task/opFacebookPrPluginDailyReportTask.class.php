<?php

class opFacebookPrPluginDailyReportTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'opFacebookPrPlugin';
    $this->name             = 'daily-report';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [opFacebookPrPlugin:daily-report|INFO] task posts daily sns report to your fanpage wall.
Call it with:

  [./symfony opFacebookPrPlugin:daily-report|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    new sfDatabaseManager($this->configuration);
    
    //pending..
  }
}