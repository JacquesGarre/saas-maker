<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Auth\Jwt;
use App\Domain\Shared\Id;
use App\Domain\Shared\EmailAddress;

interface UserRepositoryInterface {

    public function ofId(Id $id): ?User;

    public function add(User $user): void;

    public function remove(User $user): void;

    public function findOneByEmailOrId(EmailAddress $email, Id $id): ?User;

    public function findOneByEmail(EmailAddress $email): ?User;

    public function findOneByVerificationToken(VerificationToken $token): ?User;

    public function findOneByJwt(Jwt $jwt): ?User;

    public function testReset(): void;
}