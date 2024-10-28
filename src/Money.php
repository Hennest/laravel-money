<?php

declare(strict_types=1);

namespace Hennest\Money;

use Akaunting\Money\Currency;
use Akaunting\Money\Money as AkauntingMoney;
use Brick\Math\BigDecimal;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Math\RoundingMode;
use Hennest\Money\Formatters\MoneyFormatter;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\Config;
use JsonSerializable;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class Money implements Arrayable, Jsonable, JsonSerializable
{
    private const ZERO = 0;

    public int $minorUnit;

    public Currency $currency;

    public function __construct(int $minorUnit, ?Currency $currency = null)
    {
        $currency = self::currency($currency);
        $money = new AkauntingMoney($minorUnit, $currency);
        $this->minorUnit = (int) $money->getAmount();
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

        return new self(
            minorUnit: BigDecimal::of($amount)
                ->dividedBy(
                    that: BigDecimal::one(),
                    scale: $currency->getPrecision(),
                    roundingMode: RoundingMode::HALF_UP
                )
                ->multipliedBy($currency->getSubunit())
                ->toInt(),
            currency: $currency
        );
    }

    public static function zero(?Currency $currency = null): self
    {
        return new self(self::ZERO, $currency);
    }

    /**
     * @throws MathException
     * @throws RoundingNecessaryException
     */
    public function negate(): self
    {
        return new self(
            minorUnit: BigDecimal::of($this->minorUnit)->negated()->toInt(),
            currency: $this->currency
        );
    }

    /**
     * @throws MathException
     */
    public function absolute(): self
    {
        return new self(
            minorUnit: BigDecimal::of($this->minorUnit)->abs()->toInt(),
            currency: $this->currency
        );
    }

    public function format(): MoneyFormatter
    {
        return new MoneyFormatter($this);
    }

    private static function currency(?Currency $currency = null): Currency
    {
        /** @var string $configCurrency */
        $configCurrency = Config::get('money.currency');

        return $currency ?? new Currency(
            currency: $configCurrency
        );
    }


    /**
     * @return array{minorUnit: int, currency: Currency}
     */
    public function toArray(): array
    {
        return [
            'minorUnit' => $this->minorUnit,
            'currency' => $this->currency,
        ];
    }

    public function toJson($options = 0): string|false
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return array{minorUnit: int, currency: Currency}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->format()->withSymbol();
    }
}
