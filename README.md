# OS2Forms webform submission log

Sends notification mails on select log messages on webform submissions.

## Installation

```sh
composer require os2forms/os2forms_webform_submission_log
drush pm:enable os2forms_webform_submission_log
```

## Usage

Go to webform setting » Third party settings » OS2Forms » OS2Forms webform
submissions log

## Coding standards

```sh
docker run --rm --interactive --tty --volume ${PWD}:/app itkdev/php7.4-fpm:latest composer install
docker run --rm --interactive --tty --volume ${PWD}:/app itkdev/php7.4-fpm:latest composer coding-standards-check

docker run --rm --interactive --tty --volume ${PWD}:/app node:18 yarn --cwd /app install
docker run --rm --interactive --tty --volume ${PWD}:/app node:18 yarn --cwd /app coding-standards-check
```

## Code analysis

```sh
docker run --rm --interactive --tty --volume ${PWD}:/app itkdev/php7.4-fpm:latest composer install
docker run --rm --interactive --tty --volume ${PWD}:/app itkdev/php7.4-fpm:latest composer code-analysis
```
