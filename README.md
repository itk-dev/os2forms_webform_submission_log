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

## Development

We use [DDEV Drupal Contrib](https://github.com/ddev/ddev-drupal-contrib) for development[^1].

[^1]: The DDEV environment has been created by running

      ```shell
      ddev config --project-type=drupal10 --docroot=Drupal
      ddev add-on get ddev/ddev-drupal-contrib
      ddev start
      ddev poser --no-interaction
      ddev symlink-project
      # Detect expected Drupal and PHP versions.
      ddev config --update
      ```

      (cf. <https://github.com/ddev/ddev-drupal-contrib#install>)

Start the show by running

```shell
task ddev:start
```

Run `task` to see other useful development tasks.
