<?php

declare(strict_types=1);

namespace App\Domain\Email;

use App\Domain\Shared\EmailAddress;
use App\Domain\Shared\Id;
use App\Domain\User\User;

final class UserVerificationEmail extends Email {

    public const TEMPLATE_NAME = 'user_verification_email';
    public const VERIFY_URI = '/verify';

    public static function fromUser(
        EmailSenderInterface $sender,
        TemplateRendererInterface $renderer,
        EmailAddress $from,
        User $user
    ): self {
        $toCollection = new EmailAddressCollection([$user->email()]);
        $html = Html::create(
            $renderer,
            new TemplateName(self::TEMPLATE_NAME),
            [
                'user' => $user->toArray()
            ]
        );
        return new self(
            new Id(),
            $sender,
            $from,
            $toCollection,
            new Subject("Welcome ".$user->fullName()."! Please verify your email address"),
            $html
        );
    }
}