<?php

namespace App\OIE;

use App\Indicators\IndicatorEngine;

class OrganizationalHistory
{
    private IndicatorEngine $engine;

    public function __construct(?IndicatorEngine $engine = null)
    {
        $this->engine = $engine ?? new IndicatorEngine();
    }

    public function snapshots(int $companyId, ?int $surveyId = null): array
    {
        return $this->engine->history($companyId, $surveyId);
    }

    public function latest(int $companyId, ?int $surveyId = null): ?array
    {
        $snapshots = $this->snapshots($companyId, $surveyId);

        return empty($snapshots) ? null : end($snapshots);
    }

    public function previous(int $companyId, ?int $surveyId = null): ?array
    {
        $snapshots = $this->snapshots($companyId, $surveyId);

        $count = count($snapshots);

        return $count >= 2 ? $snapshots[$count - 2] : null;
    }

    public function series(int $companyId, ?int $surveyId = null): array
    {
        return $this->snapshots($companyId, $surveyId);
    }
}
