<?php

namespace App\ReadModel\User;

class InfoView
{
    public function __construct(
        public string $email,
        public string $name,
        public string $status,
    ) {

    }
}
