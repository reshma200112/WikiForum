<?php

return [
    'validations' => [
        '1' => 'required|email',
        '2' => array('/\s{2,}/', '/[\t\n]/'),
        '3' => 'required|max:5'

    ]
];
