<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use App\Entity\Categoria;


class ContainsAlphanumericValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Istree) {
            throw new UnexpectedTypeException($constraint, IsTree::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof Categoria) {
            throw new UnexpectedValueException($value, 'Categoria');
        }

        $padre = $value->getPadre();

        if($this->esAncestro($padre, $value))
        {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ categoria }}', $value)
            ->setParameter('{{ padre }}', $padre)
            ->addViolation();
        }
    }

    private function esAncestro($value, $categoria)
    {
        if($value === null) return false;
        if($value->getId() === $categoria->getId()) return true;
        else return esAncestro($value->getPadre(), $categoria);
    }
}