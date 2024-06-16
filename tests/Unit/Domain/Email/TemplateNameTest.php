<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Email;

use App\Domain\Email\TemplateName;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

final class TemplateNameTest extends TestCase {

    public function testConstructor(): void
    {   
        $value = Factory::create()->text();
        $templateName = new TemplateName($value);
        self::assertEquals($value, $templateName->value);
    }
}