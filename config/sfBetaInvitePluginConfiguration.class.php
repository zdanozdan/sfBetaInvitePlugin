<?php

/**
 * sfBetaInvitePlugin configuration.
 * 
 * @package     sfBetaInvitePlugin
 * @subpackage  config
 * @author      Your name here
 * @version     SVN: $Id: PluginConfiguration.class.php 17207 2009-04-10 15:36:26Z Kris.Wallsmith $
 */
class sfBetaInvitePluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '1.0.0-DEV';

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if (sfConfig::get('app_sf_beta_invite_plugin_routes_register', true) && in_array('sfInvite', sfConfig::get('sf_enabled_modules', array())))
      {
	$this->dispatcher->connect('routing.load_configuration', array('sfInviteRouting', 'listenToRoutingLoadConfigurationEvent'));
      }
  }
}
