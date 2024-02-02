<?php

declare(strict_types=1);

use Akaunting\Money\Currency;
use Hennest\Money\Formatters\MoneyFormatter;
use Hennest\Money\Money;

it('can format money as minor unit', function (): void {
    $formatter = new MoneyFormatter(
        money: new Money(10_00)
    );

    expect($formatter->asMinorUnit())->toEqual(10_00);
});

it('can format money as major unit', function (): void {
    $formatter = new MoneyFormatter(
        money: new Money(10_00)
    );

    expect($formatter->asMajorUnit())->toEqual(10);
});

it('can format money to absolute value', function (): void {
    $formatter = new MoneyFormatter(
        money: new Money(-10_00)
    );

    expect($formatter->absolute())->toEqual(10_00);
});

it('can format money without symbol', function (): void {
    $formatter = new MoneyFormatter(
        money: new Money(10_00)
    );

    expect($formatter->withoutSymbol())->toEqual(10);
});

it('can format money with symbol', function (): void {
    $formatter = new MoneyFormatter(
        money: new Money(10_00, $currency = new Currency('usd'))
    );

    expect($formatter->withSymbol())->toEqual("{$currency->getSymbol()}10.00");
});

it('can format money with code', function (): void {
    $formatter = new MoneyFormatter(
        money: new Money(10_00, $currency = new Currency('usd'))
    );

    expect($formatter->withCode())->toEqual("10.00 {$currency->getCurrency()}");
});

it('can format money for humans with abbreviation', function (): void {
    $formatter = new MoneyFormatter(
        money: new Money(1_345_000_00, $currency = new Currency('usd'))
    );

    expect($formatter->forHumans(abbreviate: true))->toEqual("{$currency->getSymbol()}1.34M");
});

it('can format money for humans without abbreviation', function (): void {
    $formatter = new MoneyFormatter(
        money: new Money(1_345_000_00, $currency = new Currency('usd'))
    );

    expect($formatter->forHumans())->toEqual("{$currency->getSymbol()}1.34 million");
});
