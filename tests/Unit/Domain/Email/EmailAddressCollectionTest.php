<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Email;

use App\Domain\Email\EmailAddressCollection;
use App\Domain\Shared\EmailAddress;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class EmailAddressCollectionTest extends TestCase {

    public function testEmailAddressCollection(): void
    {
        $emails = new EmailAddressCollection();
        self::assertEquals(0, $emails->count());

        $email = EmailAddress::fromString(Factory::create()->email());
        $emails->add($email);
        self::assertEquals(1, $emails->count());

        $emails->clear();
        self::assertEquals(0, $emails->count());
    }
}