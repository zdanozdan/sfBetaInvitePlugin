<?php

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Tomasz Zdanowski <tomasz@mikran.pl>
 */
class sfInviteRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();

    // prepend our routes
    $actions = array(
      'invite_new'    => 'inviteNew',
      'invite_create' => 'inviteCreate',
    );

    foreach ($actions as $route => $action)
    {
      $r->prependRoute('sf_beta_invite_plugin_' . $route, new sfRoute('/invite/when/lauched/' . $action, array(
        'module' => 'sfInvite', 'action' => $action,
      )));
    }
  }
}
