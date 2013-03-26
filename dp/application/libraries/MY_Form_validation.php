<?php
class MY_Form_validation extends CI_Form_validation
{
 /**
 * Check if provided field has an error in $this->_field_data
 *
 * @access public
 * @param string
 * @return bool
 */
 function error_exists($field)
 {
  if(!empty($this->_field_data[$field]['error']))
  {
   return TRUE;
  }
  return FALSE;
 }
}  