<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

test('reports include monthly sales totals on sqlite', function () {
    Product::create([
        'name' => 'Test product',
        'price' => 10,
        'quantity' => 3,
        'category' => 'Test',
        'created_date' => '2026-01-15',
    ]);

    $response = $this->get('/reports');

    $response->assertOk()
        ->assertSeeText('Reports & Analytics')
        ->assertViewHas('monthlySales', function (Collection $monthlySales): bool {
            return $monthlySales->count() === 12
                && (int) $monthlySales->first()->month === 1
                && (float) $monthlySales->first()->total_sales === 30.0
                && (float) $monthlySales->get(1)->total_sales === 0.0;
        });
});
