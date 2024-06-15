<?php

declare(strict_types=1);

namespace App\Domain\Email;

use App\Domain\Shared\CcCollection;
use App\Domain\Shared\BccCollection;
use App\Domain\Shared\ToCollection;

final class Email {

    public function __construct(
        public readonly From $from,
        public readonly CcCollection $ccCollection,
        public readonly BccCollection $bccCollection,
        public readonly ToCollection $toCollection,
        public readonly Subject $subject,
        public readonly Text $text,
        public readonly Html $html
    ) {
        
    }

}