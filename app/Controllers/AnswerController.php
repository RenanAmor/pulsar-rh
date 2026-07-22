<?php

namespace App\Controllers;

use App\Services\AnswerService;
use App\Services\AuthService;
use App\Services\EmployeeService;
use App\Services\SurveyQuestionService;
use App\Services\SurveyService;

class AnswerController
{
    private AnswerService $service;
    private SurveyService $surveys;
    private EmployeeService $employees;
    private SurveyQuestionService $surveyQuestions;
    private AuthService $auth;

    public function __construct()
    {
        $this->service = new AnswerService();
        $this->surveys = new SurveyService();
        $this->employees = new EmployeeService();
        $this->surveyQuestions = new SurveyQuestionService();
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
            $survey['respondents_count'] = $this->service->countRespondents((int) $survey['id']);
        }
        unset($survey);

        ob_start();
        require __DIR__ . '/../Views/answers/index.php';
        return ob_get_clean();
    }

    public function respondents(): string
    {
        $this->protect();

        $surveyId = (int) ($_GET['survey_id'] ?? 0);

        $survey = $this->surveys->find($surveyId);

        if (!$survey) {
            header('Location: ' . BASE_URL . '/answers');
            exit;
        }

        $totalQuestions = count($this->surveyQuestions->allForSurvey($surveyId));
        $respondents = $this->service->respondentsForSurvey($surveyId);

        ob_start();
        require __DIR__ . '/../Views/answers/respondents.php';
        return ob_get_clean();
    }

    public function view(): string
    {
        $this->protect();

        $surveyId = (int) ($_GET['survey_id'] ?? 0);
        $employeeId = (int) ($_GET['employee_id'] ?? 0);

        $survey = $this->surveys->find($surveyId);
        $employee = $this->employees->find($employeeId);

        if (!$survey || !$employee) {
            header('Location: ' . BASE_URL . '/answers');
            exit;
        }

        $answers = $this->service->allForEmployeeSurvey($surveyId, $employeeId);

        ob_start();
        require __DIR__ . '/../Views/answers/view.php';
        return ob_get_clean();
    }

    public function start(): string
    {
        $this->protect();

        $surveyId = (int) ($_GET['survey_id'] ?? 0);

        $survey = $this->surveys->find($surveyId);

        if (!$survey) {
            header('Location: ' . BASE_URL . '/answers');
            exit;
        }

        $companyEmployees = array_values(array_filter(
            $this->employees->listForSelect(),
            fn (array $employee): bool =>
                (int) $employee['company_id'] === (int) $survey['company_id']
                && $employee['status'] === 'Ativo'
        ));

        ob_start();
        require __DIR__ . '/../Views/answers/start.php';
        return ob_get_clean();
    }

    public function begin(): void
    {
        $this->protect();

        $surveyId = (int) $_POST['survey_id'];
        $employeeId = (int) $_POST['employee_id'];

        header('Location: ' . BASE_URL . '/answers/apply?survey_id=' . $surveyId . '&employee_id=' . $employeeId);
        exit;
    }

    public function apply(): string
    {
        $this->protect();

        $surveyId = (int) ($_GET['survey_id'] ?? 0);
        $employeeId = (int) ($_GET['employee_id'] ?? 0);

        $survey = $this->surveys->find($surveyId);
        $employee = $this->employees->find($employeeId);

        if (!$survey || !$employee) {
            header('Location: ' . BASE_URL . '/answers');
            exit;
        }

        $questions = $this->surveyQuestions->allForSurvey($surveyId);

        $existingAnswers = [];

        foreach ($this->service->allForEmployeeSurvey($surveyId, $employeeId) as $answer) {
            $existingAnswers[(int) $answer['question_id']] = $answer;
        }

        ob_start();
        require __DIR__ . '/../Views/answers/apply.php';
        return ob_get_clean();
    }

    public function submit(): void
    {
        $this->protect();

        $surveyId = (int) $_POST['survey_id'];
        $employeeId = (int) $_POST['employee_id'];

        $survey = $this->surveys->find($surveyId);
        $questions = $this->surveyQuestions->allForSurvey($surveyId);
        $submitted = $_POST['answers'] ?? [];

        foreach ($questions as $question) {
            $questionId = (int) $question['question_id'];
            $value = $submitted[$questionId] ?? '';
            $value = is_string($value) ? trim($value) : $value;

            if ($value === '' || $value === null) {
                continue;
            }

            $score = null;
            $text = null;

            if ($question['answer_type'] === 'Escala') {
                $score = (float) $value;
            } else {
                $text = (string) $value;
            }

            $this->service->save([
                'company_id'  => (int) $survey['company_id'],
                'survey_id'   => $surveyId,
                'employee_id' => $employeeId,
                'question_id' => $questionId,
                'score'       => $score,
                'answer_text' => $text
            ]);
        }

        header('Location: ' . BASE_URL . '/answers/complete?survey_id=' . $surveyId . '&employee_id=' . $employeeId);
        exit;
    }

    public function complete(): string
    {
        $this->protect();

        $surveyId = (int) ($_GET['survey_id'] ?? 0);
        $employeeId = (int) ($_GET['employee_id'] ?? 0);

        $survey = $this->surveys->find($surveyId);
        $employee = $this->employees->find($employeeId);

        if (!$survey || !$employee) {
            header('Location: ' . BASE_URL . '/answers');
            exit;
        }

        $totalQuestions = count($this->surveyQuestions->allForSurvey($surveyId));
        $answeredQuestions = $this->service->countAnsweredQuestions($surveyId, $employeeId);

        ob_start();
        require __DIR__ . '/../Views/answers/complete.php';
        return ob_get_clean();
    }
}
