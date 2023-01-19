<?php

namespace Drupal\os2forms_webform_submission_log;

use Drupal\Core\Logger\LoggerChannelTrait;
use Drupal\Core\Mail\MailManager;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class WebformSubmissionLogMailer {
  use LoggerChannelTrait;

  /**
   * The config factory service interface.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface;
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * The mail manager service.
   *
   * @var \Drupal\Core\Mail\MailManager;
   */
  protected MailManager $mailManager;

  /**
   * The language manager service.
   *
   * @var \Drupal\Core\Language\LanguageManager;
   */
  protected LanguageManager $languageManager;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected RequestStack $requestStack;

  /**
   * Mailer constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory service interface.
   * @param \Drupal\Core\Mail\MailManager $mailManager
   *   The mail manager service.
   * @param \Drupal\Core\Language\LanguageManager $languageManager
   *   The language manager service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   */
  public function __construct(ConfigFactoryInterface $configFactory, MailManager $mailManager, LanguageManager $languageManager, RequestStack $requestStack) {
    $this->configFactory = $configFactory;
    $this->mailManager = $mailManager;
    $this->languageManager = $languageManager;
    $this->requestStack = $requestStack;
  }

  /**
   * Send "failed job" mail to recipients related to webform.
   *
   * @param $webformSubmission
   *   The webform submission that failed.
   * @param $context
   *   The logging context.
   * @return void
   */
  public function sendMails($webformSubmission, $context) {
    $webform = $webformSubmission->getWebform();
    $webformSettings = $webform->getThirdPartySetting('os2forms', 'os2forms_webform_submission_log');

    $recipients = $this->getRecipients($webformSettings);
    $module = 'os2forms_webform_submission_log';
    $key = 'submission_log_notification';
    $langCode = $this->languageManager->getCurrentLanguage()->getId();

    $params['title'] = 'Webform submission error - Form name: '. $webform->label() . ', Id: ' . $webformSubmission->serial();
    $params['from'] = $this->configFactory->get('system.site')->get('mail');
    $params['message'] = $this->createMessage($webform, $webformSubmission, $context);

    foreach ($recipients as $to) {
      $result = $this->mailManager->mail($module, $key, $to, $langCode, $params);
      if ($result['result'] !== true) {
        $logger = $this->getLogger($module);
        $logger->error('There was a problem sending your email notification');
      }
    }
  }

  /**
   * Get recipients.
   *
   * @param $webformSettings
   *
   * @return false|string[]
   */
  private function getRecipients($webformSettings) {
    return array_filter(array_map('trim', explode(PHP_EOL, $webformSettings['emails'])));
  }

  /**
   * Create the message to send.
   *
   * @param $webform
   *   The related webform.
   * @param $webformSubmission
   *   The related webform submission.
   * @param $context
   *   The related context.
   *
   * @return string
   *   The created message.
   */
  private function createMessage($webform, $webformSubmission, $context): string {
    $site_url = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();

    $message = "A webform handler failed to do its job\r\n";
    $message .= "\r\n";
    $message .= "Form name: " . $webform->label() . "\r\n";
    $message .= "Submission id: " . $webformSubmission->serial() . "\r\n";
    if(isset($context['operation'])) {
      $message .= "Operation attempted: " . $context['operation'] . "\r\n";
    }
    $message .= "Reference: " . $site_url . "/admin/structure/webform/manage/" . $webform->id() . "/submission/" . $webformSubmission->id() . "/log\r\n";

    return $message;
  }
}