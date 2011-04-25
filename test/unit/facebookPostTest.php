<?php

include(dirname(__FILE__).'/../bootstrap/unit.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);
new sfDatabaseManager($configuration);

$ret = opFacebookPrPluginClient::postWall('this is test', 'http://sns.openpne.jp/');

$t = new lime_test(1, new lime_output_color());
$t->ok($ret);