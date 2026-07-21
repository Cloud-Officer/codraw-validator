<?php

namespace Draw\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class RemoteFileExistsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof RemoteFileExists) {
            throw new UnexpectedTypeException($constraint, RemoteFileExists::class);
        }

        if (null === $value) {
            return;
        }

        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!$this->remoteFileExists($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation()
            ;
        }
    }

    public function remoteFileExists(string $url): bool
    {
        if ($handle = @fopen($url, 'r')) {
            fclose($handle);

            return true;
        }

        return false;
    }
}
