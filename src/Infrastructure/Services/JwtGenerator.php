<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Domain\Auth\JwtGeneratorInterface;
use App\Domain\Shared\Id;
use App\Domain\User\User;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

final class JwtGenerator implements JwtGeneratorInterface {

    private Configuration $config;

    public function __construct(
        private readonly string $appName,
        private readonly string $jwtExpirationTime,
        private readonly string $appSecret
     ) {
        $this->config = Configuration::forSymmetricSigner(new Sha256(), Key\InMemory::plainText($this->appSecret));
    }

    public function fromUser(User $user): string
    {
        $now = new \DateTimeImmutable();
        return $this->config->builder()
            ->issuedBy($this->appName)
            ->permittedFor($this->appName)
            ->identifiedBy((new Id())->value->toString(), true)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify("+{$this->jwtExpirationTime} seconds"))
            ->withClaim('uid', $user->id->value->toString())
            ->withClaim('email', $user->email()->value)
            ->getToken($this->config->signer(), $this->config->signingKey())
            ->toString();
    }
}