<?php

declare(strict_types=1);

use Akaunting\Money\Currency;
use Hennest\Money\Money;

it('returns string value of money', function (): void {
    expect((string) new Money(1000))->toEqual('$10.00');
});

it('can convert minor unit to money', function (): void {
    expect(Money::minorUnit(1000))->toEqual('$10.00');
});

it('can convert major unit to money', function (): void {
    expect(Money::majorUnit(10))->toEqual('$10.00');
});

it('returns zero money', function (): void {
    expect(Money::zero())->toEqual('$0.00');
});

it('can convert money to array', function (): void {
    $currency = new Currency('usd');

    expect(new Money(1000, $currency))->toMatchArray([
        'minorUnit' => 1000,
        'currency' => $currency,
    ]);
});

it('can convert money to json string', function (): void {
    $currency = new Currency('usd');

    expect((new Money(1000, $currency))->toJson())->toBeJson()->toBeString();
});

it('can serialize money to array', function (): void {
    $currency = new Currency('usd');

    expect((new Money(1000, $currency))->jsonSerialize())->toMatchArray([
        'minorUnit' => 1000,
        'currency' => $currency,
    ]);
});
