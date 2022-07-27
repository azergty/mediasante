<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */

final class ConstraintPhoneValidator extends ConstraintValidator
{
    
    public function validate($value, Constraint $constraint)
    {
        $pattern = '/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/Ui';

        $ok = preg_match( $pattern,$value, $outs);
        if($ok) {
            return;
        }
        else{

            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }

    }


}