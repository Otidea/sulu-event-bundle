## Installation

### Install the bundle

Execute the following [composer](https://getcomposer.org/) command to add the bundle to the dependencies of your
project:

```bash
composer require otd/sulu-event-bundle
```

### Enable the bundle

Enable the bundle by adding it to the list of registered bundles in the `config/bundles.php` file of your project:

 ```php
 return [
     /* ... */
     Otd\SuluEventBundle\OtdSuluEventBundle::class => ['all' => true],
 ];
 ```

### Update schema

```shell script
bin/console doctrine:schema:update --force
```

## Bundle Config

If not existing, create a `config/routes/otd_sulu_bundles_admin.yaml` file in your project and add the following configuration:
```yaml
otd_sulu_events_api:
  type: rest
  resource: otd_sulu_event.event_controller
  prefix: /admin/api
  name_prefix: otd_sulu.
```

In `config/packages/doctrine.yaml` file, add the following configuration:
```yaml
doctrine:
  orm:
    mappings:
      OtdSuluEventBundle:
        is_bundle: true
        type: xml
        dir: 'sulu-event-bundle/Resources/config/doctrine'
        prefix: 'Otd\SuluEventBundle\Entity'
        alias: OtdSuluEventBundle
```

## Role Permissions

If this bundle is being added to a previous Sulu installation, you will need to manually add the permissions to your
admin user role(s) under the `Settings > User roles` menu option.
