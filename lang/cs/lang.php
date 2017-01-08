<?php

return [
    'diff' => [
        'empty' => 'nyní',
        'ago' => [
            'year' => 'před rokem|[2, Inf]před :count roky',
            'month' => 'před měsícem|[2, Inf]před :count měsíci',
            'day' => '{1}včera|{2}předevčírem|[3, Inf]před :count dny',
            'hour' => 'před hodinou|[2, Inf]před :count hodinami',
            'minute' => 'před minutou|[2, Inf]před :count minutami',
            'second' => 'před sekundou|[2, Inf]před :count sekundami',
        ],
        'in' => [
            'second' => '{1}za sekundu|[2, 4]za :count sekundy|[5, Inf]za :count sekund',
            'hour' => '{1}za hodinu|[2, 4]za :count hodiny|[5, Inf]za :count hodin',
            'minute' => '{1}za minutu|[2, 4]za :count minuty|[5, Inf]za :count minut',
            'day' => '{1}zítra|{2}pozítří|[3, 4]za :count dny|[5, Inf]za :count dnů',
            'month' => '{1}za měsíc|[2, 4]za :count měsíce|[5, Inf]za :count měsíců',
            'year' => '{1}za rok|[2, 4]za :count roky|[5, Inf]za :count let',
        ],
    ],
];
