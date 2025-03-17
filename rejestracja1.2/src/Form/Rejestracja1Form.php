<?php

namespace Drupal\rejestracja1\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Implements a registration form with AJAX.
 */
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
    $form['#prefix'] = '<div id="drupal-form">';
	$form['#suffix'] = '</div>';

    $form['intro_text'] = [
      '#markup' => '<h3>Zapisz się już dziś!</h3>',
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

    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Numer telefonu'),
      '#required' => TRUE,
      '#maxlength' => 15,
      '#description' => $this->t('Wpisz numer telefonu w formacie +48123456789.'),
    ];

    $form['postal_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Kod pocztowy'),
      '#required' => TRUE,
      '#maxlength' => 6,
      '#description' => $this->t('Wpisz kod pocztowy w formacie XX-XXX.'),
    ];

    $form['region'] = [
      '#type' => 'select',
      '#title' => $this->t('Województwo'),
      '#required' => TRUE,
      '#options' => [
        '' => $this->t('- Wybierz województwo -'),
        'dolnoslaskie' => $this->t('Dolnośląskie'),
        'kujawsko-pomorskie' => $this->t('Kujawsko-Pomorskie'),
        'lubelskie' => $this->t('Lubelskie'),
        'lubuskie' => $this->t('Lubuskie'),
        'lodzkie' => $this->t('Łódzkie'),
        'malopolskie' => $this->t('Małopolskie'),
        'mazowieckie' => $this->t('Mazowieckie'),
        'opolskie' => $this->t('Opolskie'),
        'podkarpackie' => $this->t('Podkarpackie'),
        'podlaskie' => $this->t('Podlaskie'),
        'pomorskie' => $this->t('Pomorskie'),
        'slaskie' => $this->t('Śląskie'),
        'swietokrzyskie' => $this->t('Świętokrzyskie'),
        'warminsko-mazurskie' => $this->t('Warmińsko-Mazurskie'),
        'wielkopolskie' => $this->t('Wielkopolskie'),
        'zachodniopomorskie' => $this->t('Zachodniopomorskie'),
      ],
    ];

    $form['captcha'] = [
      '#type' => 'captcha',
      '#captcha_type' => 'default',
      '#prefix' => '<div id="captcha-wrapper">',
      '#suffix' => '</div>',
      '#ajax' => [
        'callback' => '::validateCaptchaAjax',
        'wrapper' => 'captcha-wrapper',
      ],
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Potwierdź'),
      '#ajax' => [
        'callback' => '::ajaxSubmitCallback',
        'wrapper' => 'drupal-form',
      ],
    ];

    $form['messages'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'form-messages-wrapper'],
      '#markup' => '',
    ];

    return $form;
  }

  /**
   * Walidacja danych w formularzu.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (trim($form_state->getValue('first_name')) === '') {
      $form_state->setErrorByName('first_name', $this->t('Pole "Imię" jest wymagane.'));
    }

    if (trim($form_state->getValue('last_name')) === '') {
      $form_state->setErrorByName('last_name', $this->t('Pole "Nazwisko" jest wymagane.'));
    }

    if (trim($form_state->getValue('dob')) === '') {
      $form_state->setErrorByName('dob', $this->t('Pole "Data urodzenia" jest wymagane.'));
    }

    if (trim($form_state->getValue('fav_number')) === '') {
      $form_state->setErrorByName('fav_number', $this->t('Pole "Ulubiona liczba" jest wymagane.'));
    }

    if (trim($form_state->getValue('email')) === '' || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Proszę podać poprawny adres email.'));
    }

    if (trim($form_state->getValue('phone')) === '' || !preg_match('/^\+?[0-9]{9,15}$/', $form_state->getValue('phone'))) {
      $form_state->setErrorByName('phone', $this->t('Proszę podać poprawny numer telefonu.'));
    }

    if (trim($form_state->getValue('postal_code')) === '' || !preg_match('/^\d{2}-\d{3}$/', $form_state->getValue('postal_code'))) {
      $form_state->setErrorByName('postal_code', $this->t('Proszę podać poprawny kod pocztowy.'));
    }

    if (trim($form_state->getValue('region')) === '') {
      $form_state->setErrorByName('region', $this->t('Pole "Województwo" jest wymagane.'));
    }
  }


  /**
   * AJAX callback for CAPTCHA validation.
   */
  public function validateCaptchaAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    $captcha_manager = \Drupal::service('captcha.manager');
    $is_valid = $captcha_manager->isCaptchaValid($form, $form_state);

    if ($is_valid) {
      $response->addCommand(new HtmlCommand('#captcha-wrapper', '<div class="success-message">CAPTCHA poprawna!</div>'));
    } else {
      $response->addCommand(new HtmlCommand('#captcha-wrapper', '<div class="error-message">Niepoprawna CAPTCHA. Spróbuj ponownie.'));
    }

    return $response;
  }

  /**
   * AJAX callback for the submit button.
   */
  public function ajaxSubmitCallback(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    // Sprawdzenie, czy są błędy walidacji.
    if ($form_state->hasAnyErrors()) {
      // Wyświetlenie błędów walidacji (domyślnie obsługiwanych przez Drupal).
      $response->addCommand(new HtmlCommand('#form-messages-wrapper', '<div class="error-message">Proszę poprawić błędy w formularzu.</div>'));
      return $response;
    }

    // Komunikat o sukcesie, gdy walidacja się powiedzie.
    $response->addCommand(new HtmlCommand('#form-messages-wrapper', '<div class="success-message">Formularz został przesłany!</div>'));

    // Ręczne resetowanie pól formularza.
    $form_state->setValue('first_name', '');
    $form_state->setValue('last_name', '');
    $form_state->setValue('dob', '');
    $form_state->setValue('fav_number', '');
    $form_state->setValue('email', '');
    $form_state->setValue('phone', '');
    $form_state->setValue('postal_code', '');
    $form_state->setValue('region', '');

    // Przebudowa formularza, aby wyczyścić jego pola.
    $form_state->setRebuild(TRUE);

    return $response;
  }
  
  /**
   * Przetwarzanie danych po przesłaniu formularza.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    Database::getConnection()->insert('rejestracja1_data')
      ->fields([
        'first_name' => $form_state->getValue('first_name'),
        'last_name' => $form_state->getValue('last_name'),
        'dob' => $form_state->getValue('dob'),
        'fav_number' => $form_state->getValue('fav_number'),
        'email' => $form_state->getValue('email'),
        'phone' => $form_state->getValue('phone'),
        'postal_code' => $form_state->getValue('postal_code'),
        'region' => $form_state->getValue('region'),
      ])
      ->execute();

    \Drupal::messenger()->addMessage($this->t('Dziękujemy za przesłanie formularza!'));
  }
}
