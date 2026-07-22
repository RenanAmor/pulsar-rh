<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\QuestionService;
use App\Services\SurveyQuestionService;
use App\Services\SurveyService;

class SurveyQuestionController
{
    private SurveyQuestionService $service;
    private SurveyService $surveys;
    private QuestionService $questions;
    private AuthService $auth;

    public function __construct()
    {
        $this->service = new SurveyQuestionService();
        $this->surveys = new SurveyService();
        $this->questions = new QuestionService();
        $this->auth = new AuthService();
    }

    private function protect(): void
    {
        if (!$this->auth->check()) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public function index(): string
    {
        $this->protect();

        $surveys = $this->surveys->all();

        foreach ($surveys as &$survey) {
            $survey['questions_count'] = $this->service->countForSurvey((int) $survey['id']);
        }
        unset($survey);

        ob_start();
        require __DIR__ . '/../Views/survey-questions/index.php';
        return ob_get_clean();
    }

    public function manage(): string
    {
        $this->protect();

        $surveyId = (int) ($_GET['survey_id'] ?? 0);

        $survey = $this->surveys->find($surveyId);

        if (!$survey) {
            header('Location: ' . BASE_URL . '/survey-questions');
            exit;
        }

        $surveyQuestions = $this->service->allForSurvey($surveyId);
        $availableQuestions = $this->service->availableQuestionsForSurvey($surveyId, (int) $survey['company_id']);

        ob_start();
        require __DIR__ . '/../Views/survey-questions/manage.php';
        return ob_get_clean();
    }

    public function add(): void
    {
        $this->protect();

        $surveyId = (int) $_POST['survey_id'];
        $questionId = (int) $_POST['question_id'];

        $this->service->add($surveyId, $questionId);

        header('Location: ' . BASE_URL . '/survey-questions/manage?survey_id=' . $surveyId);
        exit;
    }

    public function remove(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);
        $surveyId = (int) ($_GET['survey_id'] ?? 0);

        $this->service->remove($id);

        header('Location: ' . BASE_URL . '/survey-questions/manage?survey_id=' . $surveyId);
        exit;
    }

    public function toggleRequired(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);
        $surveyId = (int) ($_GET['survey_id'] ?? 0);

        $this->service->toggleRequired($id);

        header('Location: ' . BASE_URL . '/survey-questions/manage?survey_id=' . $surveyId);
        exit;
    }

    public function moveUp(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);
        $surveyId = (int) ($_GET['survey_id'] ?? 0);

        $this->service->moveUp($id, $surveyId);

        header('Location: ' . BASE_URL . '/survey-questions/manage?survey_id=' . $surveyId);
        exit;
    }

    public function moveDown(): void
    {
        $this->protect();

        $id = (int) ($_GET['id'] ?? 0);
        $surveyId = (int) ($_GET['survey_id'] ?? 0);

        $this->service->moveDown($id, $surveyId);

        header('Location: ' . BASE_URL . '/survey-questions/manage?survey_id=' . $surveyId);
        exit;
    }
}
