<?php

namespace Drupal\os2forms_webform_submission_log;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Logger\RfcLoggerTrait;
use Drupal\os2forms_webform_submission_log\Helper\WebformHelper;
use Drupal\webform\WebformSubmissionInterface;
use Psr\Log\LoggerInterface;

/**
 * Logger that listens for 'webform_submission' channel.
 */
final class WebformSubmissionLogLogger implements LoggerInterface {
  use RfcLoggerTrait;

  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = []) {
    // Only log the 'webform_submission' channel.
    if ($context['channel'] !== 'webform_submission') {
      return;
    }

    // Make sure the context contains a webform submission.
    if (!isset($context['webform_submission'])) {
      return;
    }

    $webformSubmission = $context['webform_submission'];

    if (!($webformSubmission instanceof WebformSubmissionInterface)) {
      return;
    }

    // Make sure webform submission log is enabled.
    if (!$webformSubmission->getWebform()->hasSubmissionLog()) {
      return;
    }

    $webform = $webformSubmission->getWebform();
    $settings = $webform->getThirdPartySetting('os2forms', 'os2forms_webform_submission_log');

    $logLevel = (int) ($settings['log_level'] ?? WebformHelper::LOG_LEVEL_NONE);

    if ($level <= $logLevel) {
      file_put_contents(__FILE__ . '.debug', var_export([
        'timestamp' => (new DrupalDateTime())->format(DrupalDateTime::FORMAT),
        'level' => $level,
        'message' => $message,
        'settings' => $settings,
      ], TRUE), FILE_APPEND);
    }
  }

}
