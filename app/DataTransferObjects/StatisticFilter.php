<?php

namespace App\DataTransferObjects;

class StatisticFilter
{
    public function __construct(
        public ?string $fromDate = null,
        public ?string $toDate = null
    ) {}
}
