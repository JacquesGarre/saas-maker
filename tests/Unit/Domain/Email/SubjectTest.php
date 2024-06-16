<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Email;

use App\Domain\Email\Subject;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class SubjectTest extends TestCase {

    public function testConstructor(): void
    {   
        $value = Factory::create()->text();
        $subject = new Subject($value);
        self::assertEquals($value, $subject->value);
    }
}