<?php

namespace Drupal\os2forms_webform_submission_log;

use Drupal\webform\WebformSubmissionInterface;

/**
 * SubmisionlogMailer service class.
 */
interface WebformSubmissionLogMailerInterface {

  /**
   * Send "failed job" mail to recipients related to webform.
   *
   * @param \Drupal\webform\WebformSubmissionInterface $webformSubmission
   *   The webform submission that failed.
   * @param array $context
   *   The logging context.
   *
   * @phpstan-param array<string, mixed> $context
   */
  public function sendMails(WebformSubmissionInterface $webformSubmission, array $context): void;

}
