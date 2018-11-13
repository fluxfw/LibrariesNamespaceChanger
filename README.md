LibrariesNamespaceChanger

### Usage

#### Composer
First add the following to your `composer.json` file:
```json
"require": {
  "srag/librariesnamespacechanger": ">=0.1.0"
},
"scripts": {
    "pre-autoload-dump": "srag\\LibrariesNamespaceChanger\\LibrariesNamespaceChanger::rewriteLibrariesNamespaces"
  }
```

And run a `composer install`.

If you deliver your plugin, the plugin has it's own copy of this library and the user doesn't need to install the library.

Hint: Because of multiple autoloaders of plugins, it could be, that different versions of this library exists and suddenly your plugin use an old version of an other plugin! So you should keep up to date your plugin with `composer update`.

### Dependencies
* PHP >=5.6
* [composer](https://getcomposer.org)

Please use it for further development!

### Adjustment suggestions
* Adjustment suggestions by pull requests on https://git.studer-raimann.ch/ILIAS/Plugins/LibrariesNamespaceChanger/tree/develop
* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/LNAMESPACECHANGER
* Bug reports under https://jira.studer-raimann.ch/projects/LNAMESPACECHANGER
* For external users please send an email to support-custom1@studer-raimann.ch

### Development
If you want development in this library you should install this library like follow:

Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/libraries
cd Customizing/global/libraries
git clone -b develop git@git.studer-raimann.ch:ILIAS/Plugins/LibrariesNamespaceChanger.git LibrariesNamespaceChanger
```
