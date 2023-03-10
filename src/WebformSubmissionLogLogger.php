<?php

namespace Drupal\os2forms_webform_submission_log;

use Drupal\Core\Logger\RfcLoggerTrait;
use Drupal\Core\Logger\RfcLogLevel;
use Drupal\webform\WebformSubmissionInterface;
use Psr\Log\LoggerInterface;

/**
 * Logger that listens for 'webform_submission' channel.
 */
final class WebformSubmissionLogLogger implements LoggerInterface {
  use RfcLoggerTrait;

  /**
   * The os2forms webform submission log mailer service.
   */
  protected WebformSubmissionLogMailerInterface $submissionLogMailer;

  /**
   * Logger constructor.
   */
  public function __construct(WebformSubmissionLogMailerInterface $submissionLogMailer) {
    $this->submissionLogMailer = $submissionLogMailer;
  }

  /**
   * Log submission.
   *
   * @param mixed $level
   *   The log level.
   * @param string $message
   *   The log message.
   * @param array $context
   *   The context.
   *
   * @phpstan-param array<string, mixed> $context
   */
  public function log($level, $message, array $context = []):void {
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

    if ($level <= RfcLogLevel::ERROR) {
      $this->submissionLogMailer->sendMails($webformSubmission, $context);
    }
  }

}
