<?php
namespace App\Enum;

enum PaysEnum: int
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
            array_map(fn($case) => $case->name, self::cases()), // Affichage en clair
            array_map(fn($case) => $case->value, self::cases()) // Stockage en base
        );
    }

    public static function fromInt(int $value): ?self
    {
        return array_search($value, array_column(self::cases(), 'value')) !== false ? self::cases()[$value - 1] : null;
    }
}

