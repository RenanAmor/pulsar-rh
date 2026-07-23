<?php

namespace App\Controllers;

use App\AI\AIManager;
use App\Services\AuthService;
use App\Services\SurveyService;

class AIController
{
    private AIManager $manager;
    private SurveyService $surveys;
    private AuthService $auth;

    public function __construct()
    {
        $this->manager = new AIManager();
        $this->surveys = new SurveyService();
        $this->auth = new AuthService();
    }

    private function protect(): void
    {
        if (!$this->auth->check()) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    private function recentExecutions(int $limit = 20): array
    {
        $path = __DIR__ . '/../../storage/ai_executions.log';

        if (!file_exists($path)) {
            return [];
        }

        $lines = array_filter(explode(PHP_EOL, file_get_contents($path)));
        $entries = array_map(fn (string $line) => json_decode($line, true), $lines);
        $entries = array_reverse($entries);

        return array_slice($entries, 0, $limit);
    }

    public function index(): string
    {
        $this->protect();

        $surveys = $this->surveys->all();
        $providers = $this->manager->providers();
        $executions = $this->recentExecutions();

        ob_start();
        require __DIR__ . '/../Views/ai/index.php';
        return ob_get_clean();
    }

    public function generate(): string
    {
        $this->protect();

        $surveyId = (int) ($_POST['survey_id'] ?? 0);

        $survey = $this->surveys->find($surveyId);
        $result = $this->manager->generateReport($surveyId);

        ob_start();
        require __DIR__ . '/../Views/ai/result.php';
        return ob_get_clean();
    }
}
