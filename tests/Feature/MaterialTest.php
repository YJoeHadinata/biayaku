<?php

use App\Models\Material;
use App\Models\User;

test('materials index page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/materials');

    $response->assertOk();
});

test('material can be created', function () {
    $user = User::factory()->create();

    $materialData = [
        'nama' => 'Biji Kopi Arabika',
        'unit' => 'kg',
        'harga_satuan' => 100000,
    ];

    $response = $this
        ->actingAs($user)
        ->post('/materials', $materialData);

    $response->assertRedirect('/materials');
    $this->assertDatabaseHas('materials', $materialData);
});

test('material creation requires valid data', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/materials', []);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['nama', 'unit', 'harga_satuan']);
});

test('material can be updated', function () {
    $user = User::factory()->create();
    $material = Material::factory()->create();

    $updatedData = [
        'nama' => 'Biji Kopi Robusta',
        'unit' => 'kg',
        'harga_satuan' => 80000,
    ];

    $response = $this
        ->actingAs($user)
        ->put("/materials/{$material->id}", $updatedData);

    $response->assertRedirect('/materials');
    $this->assertDatabaseHas('materials', $updatedData);
});

test('material can be deleted', function () {
    $user = User::factory()->create();
    $material = Material::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete("/materials/{$material->id}");

    $response->assertRedirect('/materials');
    $this->assertDatabaseMissing('materials', ['id' => $material->id]);
});
