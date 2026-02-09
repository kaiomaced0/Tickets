<?php

namespace App\Enums;

enum Prioridade: string
{
    case BAIXA = 'BAIXA';
    case MEDIA = 'MEDIA';
    case ALTA = 'ALTA';

    public function id(): int
    {
        return match ($this) {
            self::BAIXA => 1,
            self::MEDIA => 2,
            self::ALTA => 3,
        };
    }

    public static function values(): array
    {
        return array_map(static fn (self $case) => $case->value, self::cases());
    }

    public static function tryFromId(int $id): ?self
    {
        return match ($id) {
            1 => self::BAIXA,
            2 => self::MEDIA,
            3 => self::ALTA,
            default => null,
        };
    }

    public static function tryFromMixed(int|string|null $value): ?self
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value) || ctype_digit((string) $value)) {
            return self::tryFromId((int) $value);
        }

        $upper = strtoupper((string) $value);

        foreach (self::cases() as $case) {
            if ($case->value === $upper || $case->name === $upper) {
                return $case;
            }
        }

        return null;
    }
}
