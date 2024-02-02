<?php

declare(strict_types=1);

namespace Hennest\Money;

use Akaunting\Money\Currency;
use Akaunting\Money\Money as AkauntingMoney;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\RoundingNecessaryException;
use Hennest\Math\Contracts\MathServiceInterface;
use Hennest\Money\Formatters\MoneyFormatter;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\Config;
use JsonSerializable;

final readonly class Money implements Arrayable, Jsonable, JsonSerializable
{
    private const ZERO = 0;

    public int $minorUnit;

    public Currency $currency;

    public function __construct(int $minorUnit, ?Currency $currency = null)
    {
        $currency = self::currency($currency);
        $money = new AkauntingMoney($minorUnit, $currency);
        $this->minorUnit = $money->getAmount();
        $this->currency = $currency;
    }

    public static function of(int $minorUnit, ?Currency $currency = null): self
    {
        return new self($minorUnit, $currency);
    }

    public static function minorUnit(int $amount, ?Currency $currency = null): self
    {
        return new self($amount, $currency);
    }

    /**
     * @throws MathException
     * @throws RoundingNecessaryException
     */
    public static function majorUnit(float $amount, ?Currency $currency = null): self
    {
        $currency = self::currency($currency);
        $math = app(MathServiceInterface::class);

        return new self(
            minorUnit: (int) $math->multiply(
                first: $amount,
                second: $currency->getSubunit(),
                scale: $currency->getPrecision()
            ),
            currency: $currency
        );
    }

    public static function zero(?Currency $currency = null): self
    {
        return new self(self::ZERO, $currency);
    }

    public function format(): MoneyFormatter
    {
        return new MoneyFormatter($this);
    }

    private static function currency(?Currency $currency = null): Currency
    {
        return $currency ?? new Currency(
            currency: Config::get('money.currency')
        );
    }

    public function toArray(): array
    {
        return [
            'minorUnit' => $this->minorUnit,
            'currency' => $this->currency,
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->format()->withSymbol();
    }
}
