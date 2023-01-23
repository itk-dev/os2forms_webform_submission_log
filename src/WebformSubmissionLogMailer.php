<?php

namespace Drupal\os2forms_webform_submission_log;

use Drupal\Core\Logger\LoggerChannelTrait;
use Drupal\Core\Mail\MailManager;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\webform\WebformInterface;
use Drupal\webform\WebformSubmissionInterface;

/**
 * SubmisionlogMailer service class.
 */
class WebformSubmissionLogMailer {
  use LoggerChannelTrait;

  /**
   * The config factory service interface.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * The mail manager service.
   *
   * @var \Drupal\Core\Mail\MailManager
   */
  protected MailManager $mailManager;

  /**
   * The language manager service.
   *
   * @var \Drupal\Core\Language\LanguageManager
   */
  protected LanguageManager $languageManager;

  /**
   * The url generator service.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected UrlGeneratorInterface $urlGenerator;

  /**
   * Mailer constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory service interface.
   * @param \Drupal\Core\Mail\MailManager $mailManager
   *   The mail manager service.
   * @param \Drupal\Core\Language\LanguageManager $languageManager
   *   The language manager service.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $urlGenerator
   *   The URL generator.
   */
  public function __construct(ConfigFactoryInterface $configFactory, MailManager $mailManager, LanguageManager $languageManager, UrlGeneratorInterface $urlGenerator) {
    $this->configFactory = $configFactory;
    $this->mailManager = $mailManager;
    $this->languageManager = $languageManager;
    $this->urlGenerator = $urlGenerator;
  }

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
  public function sendMails(WebformSubmissionInterface $webformSubmission, array $context): void {
    /** @var ?\Drupal\webform\WebformInterface $queue */
    $webform = $webformSubmission->getWebform();
    $webformSettings = $webform->getThirdPartySetting('os2forms', 'os2forms_webform_submission_log');

    $recipients = $this->getRecipients($webformSettings);
    $module = 'os2forms_webform_submission_log';
    $key = 'submission_log_notification';
    $langCode = $this->languageManager->getCurrentLanguage()->getId();

    $params['title'] = 'Webform submission error - Form name: ' . $webform->label() . ', Id: ' . $webformSubmission->serial();
    $params['from'] = $this->configFactory->get('system.site')->get('mail');
    $params['message'] = $this->createMessage($webform, $webformSubmission, $context);

    foreach ($recipients as $to) {
      $result = $this->mailManager->mail($module, $key, $to, $langCode, $params);
      if ($result['result'] !== TRUE) {
        $logger = $this->getLogger($module);
        $logger->error('There was a problem sending your email notification');
      }
    }
  }

  /**
   * Get recipients.
   *
   * @param array $webformSettings
   *   The webform settings-.
   *
   * @phpstan-param array<string, mixed> $webformSettings
   *
   * @return array
   *   A list of recipients.
   */
  private function getRecipients(array $webformSettings): array {
    return array_filter(array_map('trim', explode(PHP_EOL, $webformSettings['emails'])));
  }

  /**
   * Create the message to send.
   *
   * @param \Drupal\webform\WebformInterface $webform
   *   The related webform.
   * @param \Drupal\webform\WebformSubmissionInterface $webformSubmission
   *   The related webform submission.
   * @param array $context
   *   The related context.
   *
   * @phpstan-param array<string, mixed> $context
   *
   * @return string
   *   The created message.
   */
  private function createMessage(WebformInterface $webform, WebformSubmissionInterface $webformSubmission, array $context): string {
    $referenceUrl = $this->urlGenerator->generateFromRoute(
      'entity.webform_submission.log',
      [
        'webform' => $webform->id(),
        'webform_submission' => $webformSubmission->id(),
      ],
      ['absolute' => TRUE]
    );

    $messageLines[] = "A webform handler failed to do its job";
    $messageLines[] = "";
    $messageLines[] = "Form name: " . $webform->label();
    $messageLines[] = "Submission id: " . $webformSubmission->serial();
    if (isset($context['operation'])) {
      $messageLines[] = "Operation attempted: " . $context['operation'];
    }
    $messageLines[] = "Reference: " . $referenceUrl;

    return implode(PHP_EOL, $messageLines);
  }

}
