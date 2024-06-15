<?php

declare(strict_types=1);

namespace App\Domain\Email;

interface EmailSenderInterface {

    public function sendEmail(Email $email): void;

}