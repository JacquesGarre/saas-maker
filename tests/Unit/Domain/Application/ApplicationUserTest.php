<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Application;

use App\Domain\Application\ApplicationUser;
use App\Tests\Stubs\Domain\Application\ApplicationStub;
use App\Tests\Stubs\Domain\User\UserStub;
use PHPUnit\Framework\TestCase;

final class ApplicationUserTest extends TestCase {

    public function testCreate(): void
    {
        $application = ApplicationStub::random();
        $user = UserStub::random();
        $appUser = ApplicationUser::create($application, $user);
        self::assertNotNull($appUser->id->value);
        self::assertEquals($application, $appUser->application);
        self::assertEquals($user, $appUser->user);
    }

    public function testToArray(): void
    {
        $application = ApplicationStub::random();
        $user = UserStub::random();
        $appUser = ApplicationUser::create($application, $user);
        $array = $appUser->toArray();
        self::assertEquals($appUser->id->value->toString(), $array['id']);
        self::assertEquals($appUser->application->toArray(), $array['application']);
        self::assertEquals($appUser->user->toArray(), $array['user']);
    }
}