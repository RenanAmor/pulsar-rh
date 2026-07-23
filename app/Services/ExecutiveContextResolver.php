<?php

namespace App\Services;

class ExecutiveContextResolver
{
    private SurveyService $surveys;

    public function __construct(?SurveyService $surveys = null)
    {
        $this->surveys = $surveys ?? new SurveyService();
    }

    /**
     * Resolve qual pesquisa deve alimentar a visão executiva.
     *
     * Não existe hoje o conceito de empresa "atual" no login — o sistema
     * resolve por requisição, como já acontece em todo o restante do
     * código. Por padrão usamos a pesquisa mais recente entre todas as
     * empresas; um survey_id ou company_id explícito tem prioridade.
     */
    public function resolveSurveyId(?int $companyId = null, ?int $surveyId = null): ?int
    {
        if ($surveyId !== null) {
            return $this->surveys->find($surveyId) ? $surveyId : null;
        }

        $all = $this->surveys->all();

        if ($companyId !== null) {
            $all = array_values(array_filter(
                $all,
                fn (array $survey): bool => (int) $survey['company_id'] === $companyId
            ));
        }

        if (empty($all)) {
            return null;
        }

        usort($all, fn (array $a, array $b): int => strtotime($b['created_at']) <=> strtotime($a['created_at']));

        return (int) $all[0]['id'];
    }
}
