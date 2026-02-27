<?php

namespace App\ReadModel\User;

class AuthView
{
    public function __construct(
        public string $id,
        public string $email,
        public string $password_hash,
        public string $name,
        public string $role,
        public string $status,
    ) {

    }
}