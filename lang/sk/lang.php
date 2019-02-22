<?php

return [
    'diff' => [
        'empty' => 'teraz',
        'ago' => [
            'year' => 'pred rokom|[2, Inf]pred :count rokmi',
            'month' => 'pred mesiacom|[2, Inf]pred :count mesiacmi',
            'day' => '{1}včera|{2}predvčerom|[3, Inf]pred :count dňami',
            'hour' => 'pred hodinou|[2, Inf]pred :count hodinami',
            'minute' => 'pred minútou|[2, Inf]pred :count minútami',
            'second' => 'pred sekundou|[2, Inf]pred :count sekundami',
        ],
        'in' => [
            'second' => '{1}za sekundu|[2, 4]za :count sekundy|[5, Inf]za :count sekúnd',
            'hour' => '{1}za hodinu|[2, 4]za :count hodiny|[5, Inf]za :count hodín',
            'minute' => '{1}za minútu|[2, 4]za :count minúty|[5, Inf]za :count minút',
            'day' => '{1}zajtra|{2}pozajtra|[3, 4]za :count dni|[5, Inf]za :count dní',
            'month' => '{1}za mesiac|[2, 4]za :count mesiace|[5, Inf]za :count mesiacov',
            'year' => '{1}za rok|[2, 4]za :count roky|[5, Inf]za :count rokov',
        ],
    ],
];
