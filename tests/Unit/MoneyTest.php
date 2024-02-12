<?php

declare(strict_types=1);

use Akaunting\Money\Currency;
use Hennest\Money\Formatters\MoneyFormatter;
use Hennest\Money\Money;

it('can be converted to string', function (): void {
    expect((string) new Money(1000))->toEqual('$10.00');
});

it('can convert minor unit to money', function (): void {
    expect(Money::minorUnit(1000))->toEqual('$10.00');
});

it('can convert major unit to money', function (): void {
    expect(Money::majorUnit(10))->toEqual('$10.00');
});

it('can return zero', function (): void {
    expect(Money::zero())->toEqual('$0.00');
});

it('can be negated', function (): void {
    expect(Money::minorUnit(1000)->negate())->toEqual('-$10.00');
});

it('can be absolute', function (): void {
    expect(Money::minorUnit(-1000)->absolute())->toEqual('$10.00');
});

it('can be formatted', function (): void {
    $money = Money::minorUnit(1000);

    expect($money->format())->toEqual(new MoneyFormatter($money));
});

it('can be converted to array', function (): void {
    $currency = new Currency('usd');

    expect(new Money(1000, $currency))->toMatchArray([
        'minorUnit' => 1000,
        'currency' => $currency,
    ]);
});

it('can be converted to json string', function (): void {
    $currency = new Currency('usd');

    expect((new Money(1000, $currency))->toJson())->toBeJson()->toBeString();
});

it('can be serialized to array', function (): void {
    $currency = new Currency('usd');

    expect((new Money(1000, $currency))->jsonSerialize())->toMatchArray([
        'minorUnit' => 1000,
        'currency' => $currency,
    ]);
});
