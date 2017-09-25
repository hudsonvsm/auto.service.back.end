<?php
return [
    'automobile' => [
        'id' => 'id',
        'license_number',
        'model_id',
        'year_of_production',
        'engine_number',
        'vin_number',
        'color_id',
        'engine_capacity',
        'description',
        'owner_id'
    ],
    'automobile_brand' => [
        'id' => 'id',
        'name'
    ],
    'automobile_brand_model' => [
        'id' => 'id',
        'name',
        'brand_id'
    ],
    'automobile_part' => [
        'id' => 'id',
        'name',
        'price'
    ],
    'automobile_part__repair_card' => [
        'id' => 'id',
        'automobile_part_id',
        'repair_card_id'
    ],
    'color' => [
        'id' => 'id',
        'name'
    ],
    'repair_card' => [
        'id' => 'id',
        'number',
        'acceptance_date',
        'start_date',
        'end_date',
        'automobile_id',
        'worker_id',
        'description',
        'total_price'
    ],
    'repair_card_data' => [
        'id' => 'id',
        'number',
        'acceptance_date',
        'start_date',
        'end_date',
        'automobile_id',
        'worker_id',
        'card_description',
        'total_price',
        'license_number',
        'model',
        'brand',
        'year_of_production',
        'engine_number',
        'vin_number',
        'color',
        'engine_capacity',
        'automobile_description',
        'owner_full_name'
    ],
    'automobile_data' => [
        'id' => 'id',
        'license_number',
        'model',
        'brand',
        'year_of_production',
        'engine_number',
        'vin_number',
        'color',
        'engine_capacity',
        'automobile_description',
        'owner_full_name'
    ],
    'client' => [
        'id' => 'id',
        'first_name',
        'last_name',
        'phone_number'
    ],
    'worker' => [
        'id' => 'id',
        'first_name',
        'last_name'
    ],
];