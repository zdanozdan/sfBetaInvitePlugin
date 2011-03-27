<?php

/**
 * sfBetaInvite form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class sfBetaInviteForm extends BasesfBetaInviteForm
{
  public function configure()
  {
    $this->validatorSchema['email'] = new sfValidatorAnd(array(
							       new sfValidatorString(array('max_length' => 255)),
							       new sfValidatorEmail(),
							       ));
    $this->useFields(array('name','email'));

  }
}
