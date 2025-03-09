<?php

namespace Drupal\rejestracja1\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class Rejestracja1Form extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rejestracja1_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Style CSS
    $form['#attached']['html_head'][] = [
      [
        '#tag' => 'style',
        '#value' => '
          @import url("https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap");
          form#custom-form {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            font-family: "Lato", sans-serif;
          }
          form#custom-form div {
            margin-bottom: 20px;
          }
          form#custom-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: 700;
          }
          form#custom-form input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1rem;
            font-family: "Lato", sans-serif;
          }
          .custom-button {
            color: white;
            background-color: #ff5722;
            border: none;
            border-radius: 5px;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            font-size: 1.2rem;
            padding: 15px 30px;
            text-decoration: none;
            cursor: pointer;
            font-family: "Lato", sans-serif;
            transition: background-color 0.3s ease;
          }
          .custom-button:hover {
            background-color: #e64a19;
          }
          h3 {
            font-family: "Lato", sans-serif;
            color: #333;
            text-align: center;
          }
        ',
      ],
      'form_styles',
    ];

    $form['#attached']['library'][] = 'core/drupal.ajax';
    $form['#attached']['drupalSettings']['custom_form'] = [
      'scrollTo' => 'custom-form',
    ];
    $form['#attached']['html_head'][] = [
      [
        '#tag' => 'script',
        '#value' => '
          (function ($, Drupal, drupalSettings) {
            Drupal.behaviors.customFormScroll = {
              attach: function (context, settings) {
                $(context).find("form").once("customFormScroll").on("submit", function () {
                  var id = drupalSettings.custom_form.scrollTo;
                  $("html, body").animate({ scrollTop: $("#" + id).offset().top }, 500);
                });
              }
            };
          })(jQuery, Drupal, drupalSettings);
        ',
      ],
      'custom_form_scroll',
    ];

    $form['intro_text'] = [
      '#markup' => '<h3 id="zapisz-sie">Zapisz się już dziś!</h3>',
    ];

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Imię'),
      '#required' => TRUE,
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nazwisko'),
      '#required' => TRUE,
    ];
    $form['dob'] = [
      '#type' => 'date',
      '#title' => $this->t('Data urodzenia'),
      '#required' => TRUE,
    ];
    $form['fav_number'] = [
      '#type' => 'number',
      '#title' => $this->t('Ulubiona liczba'),
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Adres email'),
      '#required' => TRUE,
    ];

    // CAPTCHA
    $form['captcha'] = [
      '#type' => 'captcha',
      '#captcha_type' => 'default',
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Potwierdź'),
      '#button_type' => 'primary',
      '#attributes' => [
        'class' => ['custom-button'],
        'style' => 'color:white;background-color:#ff5722;border:none;border-radius:5px;box-shadow:2px 2px 4px rgba(0, 0, 0, 0.3);font-size:1.2rem;padding:15px 30px;text-decoration:none;font-family:Lato,sans-serif;transition:background-color 0.3s ease;',
        'onmouseover' => 'this.style.backgroundColor="#e64a19"',
        'onmouseout' => 'this.style.backgroundColor="#ff5722"',
      ],
    ];

    $form['#attributes']['id'] = 'custom-form';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Proszę podać poprawny adres email.'));
    }

    $dob = $form_state->getValue('dob');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob) || strtotime($dob) === false) {
      $form_state->setErrorByName('dob', $this->t('Proszę podać poprawną datę urodzenia.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = \Drupal::database();
    $connection->insert('rejestracja1_data')
      ->fields([
        'first_name' => $form_state->getValue('first_name'),
        'last_name' => $form_state->getValue('last_name'),
        'dob' => $form_state->getValue('dob'),
        'fav_number' => $form_state->getValue('fav_number'),
        'email' => $form_state->getValue('email'),
      ])
      ->execute();

    \Drupal::messenger()->addMessage($this->t('Dziękujemy za przesłanie formularza!'));
  }

}
