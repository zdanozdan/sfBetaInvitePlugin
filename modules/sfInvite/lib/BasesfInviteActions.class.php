<?php

/**
 * Base actions for the sfBetaInvitePlugin sfInvite module.
 * 
 * @package     sfBetaInvitePlugin
 * @subpackage  sfInvite
 * @author      Tomasz Zdanowski <tomasz@mikran.pl>
 * @version     SVN: $Id$
 */
abstract class BasesfInviteActions extends sfActions
{
  public function executeInviteNew(sfWebRequest $request)
  {
    $this->form = new sfBetaInviteForm();
  }

  public function executeInviteCreate(sfWebRequest $request)
  {
    $this->form = new sfBetaInviteForm();
    $i18n = $this->getContext()->getI18n();

    $this->form->bind($request->getParameter($this->form->getName()));
    if ($this->form->isValid())
      {
	try 
	  {
	    $invite = $this->form->save();

	    $message =  $this->getPartial('invite_message_'.$this->getUser()->getCulture(),		  
					  array('email' => $invite->getEmail(),
						'name'  => $invite->getNameOrEmail()));


	    if(class_exists('sfPostMark') == false)
	      {
		throw new sfException('sfPostMarkPlugin is required for this plugin. Install plugin or overwrite this method and remove sfPostMark call');
	      }

	    //throw email message
	    sfPostMark::compose()
	      ->addTo($invite->getEmail(), $invite->getNameOrEmail())
	      ->subject($i18n->__('I18N_INVITATION_SUBJECT'))
	      ->messageHtml($message)
	      ->tag('beta invitation')
	      ->send();

	    $this->getUser()->setFlash('success',$i18n->__('I18N_INVITATION_SUCCESS'));	   
	  }
	catch(Exception $e)
	  {
	    $this->getUser()->setFlash('error',$i18n->__('I18N_INVITATION_FAILED'));
	    $this->getUser()->setFlash('error_debug','Exception: '.$e->getMessage());

	    throw $e;
	  }

	$conf = sfConfig::get('app_sf_beta_invite_partial_success');

	//template params is an array with module, action values
	if( count($conf) == 2)
	  {
	    $p = sprintf('%s/%s', $conf[0], $conf[1]);
	    return $this->renderPartial($p,array('form'=>$this->form));
	  }

	return $this->redirect('@homepage');
      }
   
    $conf = sfConfig::get('app_sf_beta_invite_partial_error');

    //template params is an array with module, action values
    if( count($conf) == 2)
      {
	$p = sprintf('%s/%s', $conf[0], $conf[1]);
	return $this->renderPartial($p,array('form'=>$this->form));
      }

    //template params is no well formed
    else if( count($conf) != 0 )
      {
	$message = sprintf('sfBetaInvite template configuration param is a YAML array like [module,partial]');
	throw new sfConfigurationException($message);
      }

    $this->setTemplate('inviteNew');
  }
}
