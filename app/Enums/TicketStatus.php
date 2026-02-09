<?php

namespace App\Enums;

enum TicketStatus: string
{
    case ABERTO = 'ABERTO';
    case EM_ANDAMENTO = 'EM_ANDAMENTO';
    case RESOLVIDO = 'RESOLVIDO';
    case CANCELADO = 'CANCELADO';

    public function id(): int
    {
        return match ($this) {
            self::ABERTO => 1,
            self::EM_ANDAMENTO => 2,
            self::RESOLVIDO => 3,
            self::CANCELADO => 4,
        };
    }

    public static function values(): array
    {
        return array_map(static fn (self $case) => $case->value, self::cases());
    }

    public static function tryFromId(int $id): ?self
    {
        return match ($id) {
            1 => self::ABERTO,
            2 => self::EM_ANDAMENTO,
            3 => self::RESOLVIDO,
            4 => self::CANCELADO,
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
