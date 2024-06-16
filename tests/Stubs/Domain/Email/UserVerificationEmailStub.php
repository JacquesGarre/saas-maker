<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\Email;

use App\Domain\Email\UserVerificationEmail;
use App\Domain\Email\EmailSenderInterface;
use App\Domain\Email\TemplateRendererInterface;
use App\Domain\Shared\EmailAddress;
use App\Tests\Stubs\Domain\User\UserStub;
use Faker\Factory;

final class UserVerificationEmailStub {

    public static function random(
        EmailSenderInterface $sender,
        TemplateRendererInterface $renderer
    ): UserVerificationEmail {   
        $user = UserStub::random();
        return UserVerificationEmail::fromUser(
            $sender,
            $renderer,
            EmailAddress::fromString(Factory::create()->email()),
            $user
        );
    }
}