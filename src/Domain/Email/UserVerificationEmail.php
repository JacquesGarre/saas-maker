<?php

declare(strict_types=1);

namespace App\Domain\Email;

use App\Domain\Email\ToCollection;
use App\Domain\Shared\Id;
use App\Domain\User\User;

final class UserVerificationEmail extends Email {

    public const TEMPLATE_NAME = 'user_verification';

    public static function fromUser(
        EmailSenderInterface $sender,
        TemplateRendererInterface $renderer,
        From $from,
        User $user
    ): self {
        $toCollection = new ToCollection();
        $toCollection->add($user->email());
        $html = Html::create(
            $renderer,
            new TemplateName(self::TEMPLATE_NAME),
            $user->toArray()
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