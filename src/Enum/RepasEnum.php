<?php
namespace App\Enum;

enum RepasEnum: int
{
    case PETIT_DEJEUNER = 1;
    case DEJEUNER = 2;
    case DINER = 3;

    public static function getChoices(): array
    {
        return array_combine(
            array_map(fn($case) => ucwords(strtolower(str_replace('_', '-', $case->name))), self::cases()), // Transforme "PETIT_DEJEUNER" -> "Petit-Déjeuner"
            array_map(fn($case) => $case->value, self::cases()) // Stockage des valeurs
        );
    }

    public static function fromInt(int $value): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return null;
    }
}
