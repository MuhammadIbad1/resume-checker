<?php

namespace App\Services\Ats\Rules;

use App\Models\RuleSetting;

interface RuleContract
{
    public function evaluate(array $parsedData, ?string $jobDescription = null, ?RuleSetting $ruleSetting = null): array;
}
