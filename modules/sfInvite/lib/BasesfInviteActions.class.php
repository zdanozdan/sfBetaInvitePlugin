<?php

/**
 * Base actions for the sfBetaInvitePlugin sfInvite module.
 * 
 * @package     sfBetaInvitePlugin
 * @subpackage  sfInvite
 * @author      Tomasz Zdanowski
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

	return $this->redirect('@homepage');
      }

    $this->setTemplate('inviteNew');
  }
}
