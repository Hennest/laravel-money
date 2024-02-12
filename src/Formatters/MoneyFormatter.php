<?php

declare(strict_types=1);

namespace Hennest\Money\Formatters;

use Akaunting\Money\Money as AkauntingMoney;
use Hennest\Money\Money;
use Illuminate\Support\Number;

final readonly class MoneyFormatter
{
    private AkauntingMoney $money;

    public function __construct(Money $money)
    {
        $this->money = new AkauntingMoney(
            amount: $money->minorUnit,
            currency: $money->currency
        );
    }

    public function asMinorUnit(): int
    {
        return (int) $this->money->getAmount();
    }

    public function asMajorUnit(): float
    {
        return $this->money->getValue();
    }

    public function withoutSymbol(): string
    {
        return $this->money->formatSimple();
    }

    public function withSymbol(): string
    {
        return $this->money->format();
    }

    public function withCode(): string
    {
        return "{$this->money->formatSimple()} {$this->money->getCurrency()->getCurrency()}";
    }

    public function forHumans(bool $abbreviate = false): string
    {
        $numberForHumans = Number::forHumans(
            $this->money->getValue(),
            maxPrecision: $this->money->getCurrency()->getPrecision(),
            abbreviate: $abbreviate
        );

        return sprintf(
            "%s%s%s%s",
            $this->money->isNegative() ? '-' : '',
            $this->money->getCurrency()->getPrefix(),
            $numberForHumans,
            $this->money->getCurrency()->getSuffix()
        );
    }
}
