<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintPhone extends Constraint
{
    public $message = "Phone number doesen't match";
    
}