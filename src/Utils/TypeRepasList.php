<?php

namespace App\Utils;

class TypeRepasList
{
    public const TYPEREPAS = [
        1 => 'Petit-déjeuner',
        2 => 'Déjeuner',
        3 => 'Dinner',
       
    ];

    public static function getTypeRepasNom(int $id): ?string
    {
        return self::TYPEREPAS[$id] ?? null;
    }

    public static function getTypeRepasId(string $nom): ?int
    {
        return array_search($nom, self::TYPEREPAS, true) ?: null;
    }

    public static function getTypeRepasListe(): array
    {
        return self::TYPEREPAS;
    }
}
