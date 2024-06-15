<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Domain\Auth\Exception\InvalidJwtException;
use App\Domain\Auth\JwtValidatorInterface;
use App\Domain\Auth\Jwt;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\Clock\SystemClock;
use DateTimeZone;

final class JwtValidator implements JwtValidatorInterface {

    private Configuration $config;

    public function __construct(
        private readonly string $appName,
        private readonly string $appSecret
     ) {
        $this->config = Configuration::forSymmetricSigner(new Sha256(), Key\InMemory::plainText($this->appSecret));
    }

    public function assertValid(Jwt $jwt): void
    {
        $token = $this->config->parser()->parse($jwt->value);
        $clock = new SystemClock(new DateTimeZone('UTC'));
        $constraints = [
            new SignedWith($this->config->signer(), $this->config->signingKey()),
            new IssuedBy($this->appName),
            new PermittedFor($this->appName),
            new LooseValidAt($clock)
        ];
        $isValid = $this->config->validator()->validate($token, ...$constraints);
        if (!$isValid) {
            throw new InvalidJwtException("Invalid jwt token");
        }
    }
}