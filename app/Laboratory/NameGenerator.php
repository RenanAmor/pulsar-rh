<?php

namespace App\Laboratory;

class NameGenerator
{
    private const FIRST_NAMES = [
        'Ana', 'Bruno', 'Carla', 'Daniel', 'Elaine', 'Fábio', 'Gabriela',
        'Hugo', 'Isabela', 'João', 'Karina', 'Lucas', 'Marina', 'Nelson',
        'Otávio', 'Patrícia', 'Rafael', 'Sofia', 'Thiago', 'Vanessa',
    ];

    private const LAST_NAMES = [
        'Almeida', 'Barbosa', 'Cardoso', 'Dias', 'Ferreira', 'Gomes',
        'Henriques', 'Lima', 'Martins', 'Nogueira', 'Oliveira', 'Pereira',
        'Ramos', 'Santos', 'Teixeira', 'Vieira',
    ];

    public function randomFullName(): string
    {
        $first = self::FIRST_NAMES[array_rand(self::FIRST_NAMES)];
        $last = self::LAST_NAMES[array_rand(self::LAST_NAMES)];

        return "{$first} {$last}";
    }

    public function uniqueTag(): string
    {
        return strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
    }

    public function fakeCpf(): string
    {
        $digits = '';

        for ($i = 0; $i < 9; $i++) {
            $digits .= random_int(0, 9);
        }

        return substr($digits, 0, 3) . '.' . substr($digits, 3, 3) . '.' . substr($digits, 6, 3) . '-' . random_int(10, 99);
    }

    public function slugEmail(string $name, string $domainTag): string
    {
        $slug = strtolower(str_replace(' ', '.', $name));
        $slug = preg_replace('/[^a-z.]/', '', $slug);

        return "{$slug}@{$domainTag}.lab.pulsarrh.local";
    }
}
