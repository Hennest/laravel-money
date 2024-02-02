<?php

declare(strict_types=1);

use Hennest\Money\Casts\MoneyCast;
use Hennest\Money\Money;
use Illuminate\Database\Eloquent\Model;

it('will throw an exception when trying to get money from a non-numeric value', function (): void {
    $model = Mockery::mock(Model::class);

    (new MoneyCast)->get($model, 'money', 'testing', []);
})->throws(UnexpectedValueException::class);

it('will throw an exception when trying to set money from a non-money value object', function (): void {
    $model = Mockery::mock(Model::class);

    (new MoneyCast)->set($model, 'money', 'testing', []);
})->throws(UnexpectedValueException::class);

it('can handle null value', function (): void {
    $model = Mockery::mock(Model::class);

    $value = (new MoneyCast)->get($model, 'money', null, []);

    expect($value)
        ->toBeInstanceOf(Money::class)
        ->toEqual(Money::of(0));
});

it('can get money', function (): void {
    $model = Mockery::mock(Model::class);

    $value = (new MoneyCast)->get($model, 'money', 1000, []);

    expect($value)
        ->toBeInstanceOf(Money::class)
        ->toEqual(Money::of(1000));
});

it('can set money', function (): void {
    $model = Mockery::mock(Model::class);

    $value = (new MoneyCast)->set($model, 'money', Money::of(1000), []);

    expect($value)
        ->toBeInt()
        ->toEqual(1000);
});
