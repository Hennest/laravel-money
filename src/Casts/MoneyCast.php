<?php

declare(strict_types=1);

namespace Hennest\Money\Casts;

use Hennest\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use UnexpectedValueException;

/**
 * @implements CastsAttributes<Money, mixed>
 */
final class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): Money
    {
        if ( ! is_numeric($value) && null !== $value) {
            throw new UnexpectedValueException(
                message: sprintf(
                    "Unexpected value type. Expected string, got %s",
                    get_debug_type($value)
                ),
            );
        }

        return Money::of((int) $value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): int
    {
        if ( ! $value instanceof Money) {
            throw new UnexpectedValueException(
                message: sprintf(
                    "Unexpected value type. Expected %s, got %s",
                    Money::class,
                    get_debug_type($value)
                ),
            );
        }

        return $value->format()->asMinorUnit();
    }
}
