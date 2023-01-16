<?php

namespace Drupal\os2forms_webform_submission_log\Helper;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\RfcLogLevel;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * The webform helper.
 */
final class WebformHelper {
  use StringTranslationTrait;

  public const LOG_LEVEL_NONE = -1;

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

    $form['third_party_settings']['os2forms']['os2forms_webform_submission_log']['log_level'] = [
      '#title' => $this->t('Log level'),
      '#type' => 'select',
      '#options' => RfcLogLevel::getLevels(),
      '#empty_value' => static::LOG_LEVEL_NONE,
      '#default_value' => $defaultValues['log_level'] ?? -1,
      '#description' => $this->t('Send a notification for all log messages with a level less than or equal to this level'),
    ];

    $form['third_party_settings']['os2forms']['os2forms_webform_submission_log']['emails'] = [
      '#title' => $this->t('Emails'),
      '#type' => 'textarea',
      '#default_value' => $defaultValues['emails'] ?? NULL,
      '#description' => $this->t('Send a notification to these email adresses (one per line)'),
    ];
  }

}
