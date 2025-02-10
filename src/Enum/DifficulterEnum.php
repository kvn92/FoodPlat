<?php
namespace App\Enum;

enum DifficulterEnum: int
{
    case FACILE = 1;
    case NORMAL = 2;
    case DIFFICILE = 3;
    

    public static function getChoices(): array
    {
        return array_combine(
            array_map(fn($case) => ucwords(strtolower(str_replace('_', '-', $case->name))), self::cases()),
            array_map(fn($case) => $case->value, self::cases()) // Stockage
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
