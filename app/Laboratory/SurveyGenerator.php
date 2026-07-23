<?php

namespace App\Laboratory;

use App\Services\QuestionService;
use App\Services\SurveyQuestionService;
use App\Services\SurveyService;

class SurveyGenerator
{
    private SurveyService $surveys;
    private QuestionService $questions;
    private SurveyQuestionService $surveyQuestions;
    private NameGenerator $names;

    public function __construct()
    {
        $this->surveys = new SurveyService();
        $this->questions = new QuestionService();
        $this->surveyQuestions = new SurveyQuestionService();
        $this->names = new NameGenerator();
    }

    private function findSurveyIdByTitle(string $title): ?int
    {
        foreach ($this->surveys->all() as $survey) {
            if ($survey['title'] === $title) {
                return (int) $survey['id'];
            }
        }

        return null;
    }

    /**
     * @return int[] IDs das pesquisas geradas, já com todas as perguntas da
     *               Biblioteca Psicométrica compatíveis com a empresa vinculadas.
     */
    public function generate(int $companyId, string $scenarioLabel, int $count): array
    {
        $surveyIds = [];

        for ($i = 0; $i < $count; $i++) {
            $tag = $this->names->uniqueTag();
            $title = "[LAB] {$scenarioLabel} - Pesquisa " . ($i + 1) . " - {$tag}";

            $this->surveys->create([
                'company_id'  => $companyId,
                'title'       => $title,
                'description' => 'Pesquisa gerada automaticamente pelo Laboratório Organizacional para fins de teste, demonstração e validação do OIE.',
                'start_date'  => (new \DateTimeImmutable())->modify('-30 days')->format('Y-m-d'),
                'end_date'    => (new \DateTimeImmutable())->modify('+30 days')->format('Y-m-d'),
                'anonymous'   => 0,
                'status'      => 'Em andamento',
            ]);

            $surveyId = $this->findSurveyIdByTitle($title);

            if ($surveyId === null) {
                continue;
            }

            foreach ($this->questions->all() as $question) {
                $isGeneric = $question['company_id'] === null;
                $isSameCompany = (int) ($question['company_id'] ?? 0) === $companyId;

                if ((int) $question['active'] === 1 && ($isGeneric || $isSameCompany)) {
                    $this->surveyQuestions->add($surveyId, (int) $question['id']);
                }
            }

            $surveyIds[] = $surveyId;
        }

        return $surveyIds;
    }
}
