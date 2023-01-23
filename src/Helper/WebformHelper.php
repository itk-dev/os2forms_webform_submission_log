<?php

namespace Drupal\os2forms_webform_submission_log\Helper;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Component\Render\FormattableMarkup;

/**
 * The webform helper.
 */
final class WebformHelper {
  use StringTranslationTrait;

  /**
   * Implements hook_webform_third_party_settings_form_alter().
   *
   * @phpstan-param array<string, mixed> $form
   */
  public function webformThirdPartySettingsFormAlter(array &$form, FormStateInterface $form_state): void {
    /** @var \Drupal\Core\Entity\EntityForm $formObject */
    $formObject = $form_state->getFormObject();
    /** @var \Drupal\webform\WebformInterface $webform */
    $webform = $formObject->getEntity();

    $defaultValues = $webform->getThirdPartySetting('os2forms', 'os2forms_webform_submission_log');
    $form['third_party_settings']['os2forms']['os2forms_webform_submission_log'] = [
      '#type' => 'details',
      '#open' => TRUE,
      '#title' => $this->t('OS2Forms webform submission log'),
      '#tree' => TRUE,
    ];

    $form['third_party_settings']['os2forms']['os2forms_webform_submission_log']['emails'] = [
      '#title' => $this->t('Emails'),
      '#type' => 'textarea',
      '#default_value' => $defaultValues['emails'] ?? NULL,
      '#description' => $this->t('Send a notification to these email adresses (one per line)'),
    ];
  }

  /**
   * Define the email templates to use for sending emails through this module.
   *
   * @param string $key
   *   The mail key.
   * @param array $message
   *   The message to send.
   * @param array $params
   *   Various parameters used by the mail template.
   */
  public function mail(string $key, array &$message, array $params) {
    if ($key == 'submission_log_notification') {
      $message['from'] = $params['from'];
      $message['subject'] = $params['title'];
      $message['body'][] = (new FormattableMarkup($params['message'], []))->__toString();
    }
  }

}
