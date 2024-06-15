<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\User\User;

interface JwtGeneratorInterface {

    public function fromUser(User $user): string;
}