<?php

/**
 * sfBetaInvite form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class PluginsfBetaInviteForm extends BasesfBetaInviteForm
{
  public function configure()
  {
    $this->useFields(array('name','email'));

    $this->validatorSchema['email'] = new sfValidatorAnd(array(
							       new sfValidatorString(array('max_length' => 255)),
							       new sfValidatorEmail(array(),array('invalid'=>'I18N_EMAIL_INVALID')),
							       ));
  }
}
