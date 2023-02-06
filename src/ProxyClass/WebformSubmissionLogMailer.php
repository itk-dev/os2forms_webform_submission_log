<?php
// phpcs:ignoreFile

/**
 * This file was generated via php core/scripts/generate-proxy-class.php 'Drupal\os2forms_webform_submission_log\WebformSubmissionLogMailer' "modules/contrib/os2forms_webform_submission_log/src".
 */

namespace Drupal\os2forms_webform_submission_log\ProxyClass {

    /**
     * Provides a proxy class for \Drupal\os2forms_webform_submission_log\WebformSubmissionLogMailer.
     *
     * @see \Drupal\Component\ProxyBuilder
     */
    class WebformSubmissionLogMailer implements \Drupal\os2forms_webform_submission_log\WebformSubmissionLogMailerInterface
    {

        use \Drupal\Core\DependencyInjection\DependencySerializationTrait;

        /**
         * The id of the original proxied service.
         *
         * @var string
         */
        protected $drupalProxyOriginalServiceId;

        /**
         * The real proxied service, after it was lazy loaded.
         *
         * @var \Drupal\os2forms_webform_submission_log\WebformSubmissionLogMailer
         */
        protected $service;

        /**
         * The service container.
         *
         * @var \Symfony\Component\DependencyInjection\ContainerInterface
         */
        protected $container;

        /**
         * Constructs a ProxyClass Drupal proxy object.
         *
         * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
         *   The container.
         * @param string $drupal_proxy_original_service_id
         *   The service ID of the original service.
         */
        public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container, $drupal_proxy_original_service_id)
        {
            $this->container = $container;
            $this->drupalProxyOriginalServiceId = $drupal_proxy_original_service_id;
        }

        /**
         * Lazy loads the real service from the container.
         *
         * @return object
         *   Returns the constructed real service.
         */
        protected function lazyLoadItself()
        {
            if (!isset($this->service)) {
                $this->service = $this->container->get($this->drupalProxyOriginalServiceId);
            }

            return $this->service;
        }

        /**
         * {@inheritdoc}
         */
        public function sendMails(\Drupal\webform\WebformSubmissionInterface $webformSubmission, array $context): void
        {
            $this->lazyLoadItself()->sendMails($webformSubmission, $context);
        }

        /**
         * {@inheritdoc}
         */
        public function setLoggerFactory(\Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory)
        {
            return $this->lazyLoadItself()->setLoggerFactory($logger_factory);
        }

    }

}
