<?php
namespace App\Enum;

enum CategorieEnum: int
{
    case ENTREE = 1;
    case PLAT = 2;
    case DESSERT = 3;
    

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
