<?php

namespace App\Model\User\UseCase\SignUp\Request;

use Symfony\Component\Validator\Constraints;

class Command
{
    #[Constraints\NotBlank]
    #[Constraints\Email]
    public string $email;

    #[Constraints\NotBlank]
    #[Constraints\Length(min: 6)]
    public string $password;

    #[Constraints\NotBlank]
    public string $name;
}
