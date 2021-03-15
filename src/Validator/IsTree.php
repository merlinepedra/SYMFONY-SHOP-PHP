<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsTree extends Constraint
{
    public $message = 'La categoria {{ categoria }} no puede tener como padre a {{ padre }} porque pertenece a la descendencia.';
}