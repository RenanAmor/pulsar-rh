<?php

namespace App\Laboratory;

use App\Services\EmployeeService;

class EmployeeGenerator
{
    private EmployeeService $employees;
    private NameGenerator $names;

    public function __construct()
    {
        $this->employees = new EmployeeService();
        $this->names = new NameGenerator();
    }

    /**
     * @param array $organization Estrutura retornada por OrganizationGenerator::generate()
     * @return int[] IDs dos colaboradores gerados
     */
    public function generate(array $organization, int $count): array
    {
        $departmentIds = $organization['department_ids'];
        $teamIds = $organization['team_ids'];
        $positionIds = $organization['position_ids'];

        $cpfs = [];

        for ($i = 0; $i < $count; $i++) {
            $slot = $i % count($departmentIds);

            $name = $this->names->randomFullName() . ' ' . ($i + 1);
            $cpf = $this->names->fakeCpf();
            $cpfs[] = $cpf;

            $admissionDaysAgo = random_int(30, 1500);
            $admissionDate = (new \DateTimeImmutable())->modify("-{$admissionDaysAgo} days")->format('Y-m-d');

            $this->employees->create([
                'company_id'       => $organization['company_id'],
                'branch_id'        => $organization['branch_id'],
                'department_id'    => $departmentIds[$slot],
                'team_id'          => $teamIds[$slot] ?? null,
                'position_id'      => $positionIds[($slot * 2) + ($i % 2)] ?? $positionIds[0],
                'manager_id'       => null,
                'registration'     => 'LAB-' . str_pad((string) ($i + 1), 5, '0', STR_PAD_LEFT),
                'name'             => $name,
                'cpf'              => $cpf,
                'birth_date'       => (new \DateTimeImmutable())->modify('-' . random_int(22, 60) . ' years')->format('Y-m-d'),
                'gender'           => 'Prefiro não informar',
                'email'            => $this->names->slugEmail($name, 'lab'),
                'phone'            => null,
                'admission_date'   => $admissionDate,
                'termination_date' => null,
                'employment_type'  => 'CLT',
                'status'           => 'Ativo',
            ]);
        }

        // Resolve todos os IDs com um único scan da tabela em vez de um scan
        // por colaborador (evita custo O(n²) ao gerar volumes maiores).
        $cpfToId = [];

        foreach ($this->employees->all() as $employee) {
            $cpfToId[$employee['cpf']] = (int) $employee['id'];
        }

        $employeeIds = [];

        foreach ($cpfs as $cpf) {
            if (isset($cpfToId[$cpf])) {
                $employeeIds[] = $cpfToId[$cpf];
            }
        }

        return $employeeIds;
    }
}
