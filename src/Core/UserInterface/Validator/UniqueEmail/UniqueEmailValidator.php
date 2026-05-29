<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Validator\UniqueEmail;

use App\Core\Application\Query\IsEmailTaken\IsEmailTakenQuery;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEmailValidator extends ConstraintValidator
{
    public function __construct(
        #[Autowire(service: 'core.query.bus')]
        private readonly MessageBusInterface $queryBus,
    ) {
    }
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!\is_string($value) || '' === trim($value)) {
            return;
        }

        if (!$constraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($constraint, UniqueEmail::class);
        }

        $exists = $this->queryBus
            ->dispatch(new IsEmailTakenQuery($value))
            ->last(HandledStamp::class)
            ?->getResult();

        if ($exists) {
            $this->context->buildViolation('validator.unique_email.already_taken')
                ->addViolation();
        }
    }
}
