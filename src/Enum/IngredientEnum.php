<?php
namespace App\Enum;

enum IngredientEnum: int
{
    case FRANCE = 1;
    case BELGIQUE = 2;
    case SUISSE = 3;
    case CANADA = 4;
    case MAROC = 5;
    case ALGERIE = 6;
    case TUNISIE = 7;
    case ITALIE = 8;
    case ESPAGNE = 9;
    case ETATS_UNIS = 10;

    public static function getChoices(): array
    {
        return array_combine(
            array_map(fn($case) => $case->name, self::cases()), // Affichage
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
