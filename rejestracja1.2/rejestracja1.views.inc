<?php

/**
 * Implements hook_views_data().
 */
function rejestracja1_views_data() {
  $data = [];

  // Rejestracja tabeli `rejestracja1_data` w systemie Views.
  $data['rejestracja1_data'] = [
    'table' => [
      'group' => t('Rejestracja1 Data'),
      'base' => [
        'field' => 'id', // Klucz główny w tabeli.
        'title' => t('Rejestracja1 Data'),
        'help' => t('Dane przesłane przez formularz rejestracji.'),
      ],
    ],
  ];

  // Rejestracja pól widocznych w Widoku.

  $data['rejestracja1_data']['first_name'] = [
    'title' => t('Imię'),
    'help' => t('Imię użytkownika.'),
    'field' => [
      'id' => 'standard', // Pole tekstowe.
    ],
  ];

  $data['rejestracja1_data']['last_name'] = [
    'title' => t('Nazwisko'),
    'help' => t('Nazwisko użytkownika.'),
    'field' => [
      'id' => 'standard', // Pole tekstowe.
    ],
  ];

  $data['rejestracja1_data']['email'] = [
    'title' => t('Adres email'),
    'help' => t('Adres email użytkownika.'),
    'field' => [
      'id' => 'standard', // Pole tekstowe.
    ],
  ];

  $data['rejestracja1_data']['phone'] = [
    'title' => t('Numer telefonu'),
    'help' => t('Numer telefonu użytkownika.'),
    'field' => [
      'id' => 'standard', // Pole tekstowe.
    ],
  ];

  $data['rejestracja1_data']['postal_code'] = [
    'title' => t('Kod pocztowy'),
    'help' => t('Kod pocztowy użytkownika.'),
    'field' => [
      'id' => 'standard', // Pole tekstowe.
    ],
  ];

  $data['rejestracja1_data']['region'] = [
    'title' => t('Województwo'),
    'help' => t('Województwo wybrane przez użytkownika.'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string', // Umożliwia filtrowanie tego pola.
    ],
  ];


  return $data;
}
