<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Listener;

use App\Domain\Application\Application;
use App\Domain\Application\ApplicationUserCollection;
use Doctrine\ORM\Event\PostLoadEventArgs;

class ApplicationPostLoadListener
{
    public function postLoad(PostLoadEventArgs $args): void
    {
        $application = $args->getObject();
        if (!$application instanceof Application) {
            return;
        }

        if ($application->users() === null) {
            $applicationReflection = new \ReflectionClass(Application::class);
            $usersProperty = $applicationReflection->getProperty('users');
            $usersProperty->setAccessible(true);
            $usersProperty->setValue($application, new ApplicationUserCollection());
        }
    }
}