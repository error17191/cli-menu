<?php
$screens = [];
$screens['main-menu'] = [
    'type' => 'menu',
    'items' => [
        '1' => [
            'label' => '1)Account',
            'reference' => 'account-menu',
        ],
        '2' => [
            'label' => '2)Services',
            'reference' => 'services-menu',
        ],
        '3' => [
            'label' => '3)Friends',
            'reference' => 'friends-menu'
        ],
        '4' => [
            'label' => '4)Settings',
            'reference' => 'settings-menu',
        ],
    ],
    'isMainMenu' => true
];

$screens['account-menu'] = [
    'backTo' => 'main-menu',
    'type' => 'menu',
    'items' => [
        '1' => [
            'label' => '1)Account Information',
            'reference' => 'account-info',
        ],
    ],

];

$screens['services-menu'] = [
    'backTo' => 'main-menu',
    'type' => 'menu',
    'items' => [
        '1' => [
            'label' => '1)Internet Service',
            'reference' => 'internet-service',
        ],
    ],
];

$screens['internet-service'] = [
    'type' => 'status',
    'status' => 'off',
    'off' => '1)Activate',
    'on' => '1)Deactivate',
    'on-msg' => 'Internet Service is Active',
    'off-msg' => 'Internet Service is not Active',
];

$screens['wrong-option'] = [
    'backTo' => 'main-menu',
    'type' => 'message',
    'message' => 'Wrong Option'
];


displayScreen('main-menu');


function displayScreen($screenKey)
{
    global $screens;
    // Clearing Screen
    echo "\e[H\e[2J";

    switch ($screens[$screenKey]['type']) {
        case 'menu':
            foreach ($screens[$screenKey]['items'] as $item) {
                echo $item['label'] . "\n";
            }
            break;
        case 'message':
            echo $screens[$screenKey]['message'] . "\n";
            break;
        case 'status':
            echo $screens[$screenKey][$screens[$screenKey]['status']];
        default;
    }

    if (isset($screens[$screenKey]['backTo'])) {
        echo "0)Back\n";
    }
    // Display Main Menu option only if it's not the main menu screen
    if (!isset($screens[$screenKey]['isMainMenu'])) {
        echo "00)Main Menu\n";
    }
    echo "000)Exit\n";
    // Get Input from The User
    $option = trim(fgets(STDIN));
    // Now handle the option entered by the user
    // Exit
    if ($option === '000') {
        echo "\e[H\e[2J";
        exit;
    }
    // Go To Main Menu
    if ($option === '00' && !isset($screens[$screenKey]['isMainMenu'])) {
        displayScreen('main-menu');
        return;
    }
    // Back
    if (isset($screens[$screenKey]['backTo']) && $option === '0') {
        displayScreen($screens[$screenKey]['backTo']);
        return;
    }
    // Go to selected menu item
    if ($screens[$screenKey]['type'] === 'menu'
        && isset($screens[$screenKey]['items'][$option])) {
        displayScreen($screens[$screenKey]['items'][$option]['reference']);
        return;
    }
    // If option is not recognized
    // Set the back of the wrong-option to current screen and go to it
    $screens['wrong-option']['backTo'] = $screenKey;
    displayScreen('wrong-option');
}
