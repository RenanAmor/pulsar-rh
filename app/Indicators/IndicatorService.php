<?php

namespace App\Indicators;

class IndicatorService
{
    public const CATEGORIES = [
        'Liderança',
        'Comunicação',
        'Engajamento',
        'Cultura',
        'Bem-estar',
        'Desenvolvimento',
        'Colaboração',
        'Reconhecimento',
    ];

    public const DIMENSIONS = [
        'Liderança',
        'Comunicação',
        'Engajamento',
        'Motivação',
        'Satisfação',
        'Cultura',
        'Clima Organizacional',
        'Pertencimento',
        'Justiça Organizacional',
        'Segurança Psicológica',
        'Bem-estar',
        'Burnout',
        'Desenvolvimento',
        'Trabalho em Equipe',
        'Reconhecimento',
    ];

    private IndicatorRepository $repository;
    private IndicatorCalculator $calculator;

    public function __construct(?IndicatorRepository $repository = null, ?IndicatorCalculator $calculator = null)
    {
        $this->repository = $repository ?? new IndicatorRepository();
        $this->calculator = $calculator ?? new IndicatorCalculator();
    }

    private function summarize(array $answers): array
    {
        $normalized = array_map(
            fn (array $answer) => $this->calculator->normalizeAnswer($answer),
            $answers
        );

        return [
            'average'         => $this->calculator->average($normalized),
            'responses_count' => count($answers),
        ];
    }

    public function calculateCategory(int $surveyId, string $category, array $scope = []): array
    {
        $answers = $this->repository->answersForScope($surveyId, $scope, $category, null);

        return array_merge(['category' => $category], $this->summarize($answers));
    }

    public function calculateDimension(int $surveyId, string $dimension, array $scope = []): array
    {
        $answers = $this->repository->answersForScope($surveyId, $scope, null, $dimension);

        return array_merge(['dimension' => $dimension], $this->summarize($answers));
    }

    public function calculateAllCategories(int $surveyId, array $scope = []): array
    {
        return array_map(
            fn (string $category) => $this->calculateCategory($surveyId, $category, $scope),
            self::CATEGORIES
        );
    }

    public function calculateAllDimensions(int $surveyId, array $scope = []): array
    {
        return array_map(
            fn (string $dimension) => $this->calculateDimension($surveyId, $dimension, $scope),
            self::DIMENSIONS
        );
    }

    public function participation(int $surveyId, array $scope = []): array
    {
        $eligible = $this->repository->eligibleEmployees($scope);
        $responded = $this->repository->respondedEmployees($surveyId, $scope);

        return [
            'eligible_employees'  => $eligible,
            'responded_employees' => $responded,
            'percentage'          => $this->calculator->participation($responded, $eligible),
        ];
    }

    public function responsesCount(int $surveyId, array $scope = []): int
    {
        return count($this->repository->answersForScope($surveyId, $scope));
    }

    public function history(int $companyId, ?int $surveyId = null): array
    {
        return $this->repository->historySnapshots($companyId, $surveyId);
    }

    public function latestSnapshot(int $companyId, ?int $surveyId = null): ?array
    {
        return $this->repository->latestSnapshot($companyId, $surveyId);
    }

    public function saveSnapshot(array $data): bool
    {
        return $this->repository->saveSnapshot($data);
    }
}
