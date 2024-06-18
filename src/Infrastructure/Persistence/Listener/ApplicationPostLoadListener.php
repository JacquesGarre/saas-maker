<?php

namespace App\Infrastructure\Persistence\Listener;

use App\Domain\Application\Application;
use App\Domain\Application\ApplicationUserCollection;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ApplicationPostLoadListener
{
    public function postLoad(LifecycleEventArgs $args): void
    {
        $application = $args->getObject();
        dump($application);

        if (!$application instanceof Application) {
            return;
        }

        dd($application);
        
        $users = $application->users();
        
        if ($users instanceof \Doctrine\ORM\PersistentCollection) {
            $applicationReflection = new \ReflectionClass(Application::class);
            $usersProperty = $applicationReflection->getProperty('users');
            $usersProperty->setAccessible(true);
            $usersProperty->setValue($application, new ApplicationUserCollection($users->toArray()));
        }
        
    }
}