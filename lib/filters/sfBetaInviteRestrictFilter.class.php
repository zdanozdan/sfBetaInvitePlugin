<?php
/**
 *
 * This filter reads restricts app usage to list of available routes
 * Those need to configured in app.yml file
 *
 * @package    sfBetaInviteFilter
 * @author     Tomasz Zdanowski <tomasz@mikran.pl>
 * @version    SVN: $Id$
 */
class sfBetaInviteRestrictFilter extends sfFilter
{
  public function execute($filterChain)
  {
    //Filters don't have direct access to the request and user objects.
    $request = $this->getContext()->getRequest();
    //$response = $this->getContext()->getResponse();

    $i18n       = $this->getContext()->getI18N();
    $user       = $this->getContext()->getUser();
    $controller = $this->getContext()->getController();
    $routing    = $this->getContext()->getRouting();
    //current route name is here
    $route      = $routing->getCurrentRouteName();

    //var_dump($routing->getCurrentRouteName());
    //die();
    $conf = sfConfig::get('app_sf_beta_invite_allowed');
    if(!in_array($route,$conf))
      {
	$user->setFlash('error',$i18n->__('I18N_INVITATION_RESTRICTED'));

	//
	// in case of popup requests just display simple HTML string error
	//
	if($request->isXmlHttpRequest())
	  {
	    $xml_message = sprintf('<div class="error large">%s</div>',$i18n->__('I18N_INVITATION_RESTRICTED'));
	    echo $xml_message;
	    throw new sfStopException();
	  }

	$controller->redirect('@homepage');
      }

    //allowed to go, execute filter chain
    $filterChain->execute();
  }     
}