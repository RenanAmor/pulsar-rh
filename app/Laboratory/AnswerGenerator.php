<?php

namespace App\Laboratory;

use App\Services\AnswerService;
use App\Services\SurveyQuestionService;
use App\Services\SurveyService;

class AnswerGenerator
{
    private AnswerService $answers;
    private SurveyQuestionService $surveyQuestions;
    private SurveyService $surveys;
    private ScenarioProfile $profile;

    public function __construct()
    {
        $this->answers = new AnswerService();
        $this->surveyQuestions = new SurveyQuestionService();
        $this->surveys = new SurveyService();
        $this->profile = new ScenarioProfile();
    }

    /**
     * @param int[] $employeeIds
     * @return int Quantidade de colaboradores que efetivamente responderam
     */
    public function generate(int $surveyId, array $employeeIds, string $scenarioKey, int $respondentsCount): int
    {
        $survey = $this->surveys->find($surveyId);

        if (!$survey) {
            return 0;
        }

        $companyId = (int) $survey['company_id'];
        $questions = $this->surveyQuestions->allForSurvey($surveyId);

        if (empty($questions) || empty($employeeIds)) {
            return 0;
        }

        $respondentsCount = max(0, min($respondentsCount, count($employeeIds)));

        $shuffled = $employeeIds;
        shuffle($shuffled);
        $respondents = array_slice($shuffled, 0, $respondentsCount);

        $rows = [];

        foreach ($respondents as $employeeId) {
            foreach ($questions as $question) {
                $rows[] = $this->buildAnswerRow($companyId, $surveyId, (int) $employeeId, $question, $scenarioKey);
            }
        }

        $this->answers->saveMany($rows);

        return count($respondents);
    }

    private function buildAnswerRow(int $companyId, int $surveyId, int $employeeId, array $question, string $scenarioKey): array
    {
        $dimension = $question['dimension'];
        $percentage = $this->profile->samplePercentage($scenarioKey, $dimension);

        $score = null;
        $text = null;

        switch ($question['answer_type']) {
            case 'Escala':
                $score = $this->profile->denormalizeToScale(
                    $percentage,
                    (int) $question['scale_min'],
                    (int) $question['scale_max']
                );
                break;

            case 'SimNão':
                $text = $this->profile->percentageToYesNo($percentage);
                break;

            case 'Múltipla Escolha':
                $text = 'Opção gerada automaticamente pelo Laboratório Organizacional';
                break;

            default:
                $text = 'Resposta gerada automaticamente pelo Laboratório Organizacional.';
                break;
        }

        return [
            'company_id'  => $companyId,
            'survey_id'   => $surveyId,
            'employee_id' => $employeeId,
            'question_id' => (int) $question['question_id'],
            'score'       => $score,
            'answer_text' => $text,
        ];
    }
}
