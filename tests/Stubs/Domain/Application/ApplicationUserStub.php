<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Domain\Application;

use App\Domain\Application\ApplicationUser;
use App\Tests\Stubs\Domain\Application\ApplicationStub;
use App\Tests\Stubs\Domain\User\UserStub;

final class ApplicationUserStub {

    public static function random(): ApplicationUser
    {
        $application = ApplicationStub::random();
        $user = UserStub::random();
        return ApplicationUser::create($application, $user);
    }
}