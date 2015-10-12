#Kreta Simple Api Doc Bundle
>Bundle that generates Api documentation on top of **[NelmioApiDocBundle][0]**.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/87e637fa-3e4e-47d1-8b27-fd5fe65e8def/mini.png)](https://insight.sensiolabs.com/projects/87e637fa-3e4e-47d1-8b27-fd5fe65e8def)
[![Build Status](https://travis-ci.org/kreta/SimpleApiDocBundle.svg?branch=master)](https://travis-ci.org/kreta/SimpleApiDocBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kreta/SimpleApiDocBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kreta/SimpleApiDocBundle/?branch=master)
[![Total Downloads](https://poser.pugx.org/kreta/simple-api-doc-bundle/downloads)](https://packagist.org/packages/kreta/simple-api-doc-bundle)
[![Latest Stable Version](https://poser.pugx.org/kreta/simple-api-doc-bundle/v/stable.svg)](https://packagist.org/packages/kreta/simple-api-doc-bundle)
[![Latest Unstable Version](https://poser.pugx.org/kreta/simple-api-doc-bundle/v/unstable.svg)](https://packagist.org/packages/kreta/simple-api-doc-bundle)

##Tests
This bundle is completely tested by **[PHPSpec][1], SpecBDD framework for PHP**.

Because you want to contribute or simply because you want to throw the tests, you have to type the following command
in your terminal.
```
$ vendor/bin/phpspec run -fpretty
```
##Contributing
This bundle follows PHP coding standards, so pull requests need to execute the Fabien Potencier's [PHP-CS-Fixer][5]
and Marc Morera's [PHP-Formatter][6]. Furthermore, if the PR creates some not-PHP file remember that you have to put
the license header manually.
```
$ vendor/bin/php-cs-fixer fix
$ vendor/bin/php-cs-fixer fix --config-file .phpspec_cs

$ vendor/bin/php-formatter formatter:use:sort src/
$ vendor/bin/php-formatter formatter:use:sort spec/
$ vendor/bin/php-formatter formatter:header:fix src/
$ vendor/bin/php-formatter formatter:header:fix spec/
```

There is also a policy for contributing to this project. Pull requests must be explained step by step to make the
review process easy in order to accept and merge them. New methods or code improvements must come paired with
[PHPSpec][1] tests.

If you would like to contribute it is a good point to follow Symfony contribution standards, so please read the
[Contributing Code][2] in the project documentation. If you are submitting a pull request, please follow the guidelines
in the [Submitting a Patch][3] section and use the [Pull Request Template][4].

If you have any doubt or maybe you want to share some opinion, you can use our **Gitter chat**.
[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/kreta/kreta?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

##To do
[]: Unit tests of [ValidationParser][7] class.
[]: Unit tests of [ApiDocExtractor][8] class.

##Credits
Kreta is created by:
>
**@benatespina** - [benatespina@gmail.com](mailto:benatespina@gmail.com)<br>
**@gorkalaucirica** - [gorka.lauzirika@gmail.com](mailto:gorka.lauzirika@gmail.com)

##Licensing Options
[![License](https://poser.pugx.org/kreta/simple-api-doc-bundle/license.svg)](https://github.com/kreta/SimpleApiDocBundle/blob/master/LICENSE)

[1]: http://www.phpspec.net/
[2]: http://symfony.com/doc/current/contributing/code/index.html
[3]: http://symfony.com/doc/current/contributing/code/patches.html#check-list
[4]: http://symfony.com/doc/current/contributing/code/patches.html#make-a-pull-request
[5]: http://cs.sensiolabs.org/
[6]: https://github.com/mmoreram/php-formatter

[7]: https://github.com/kreta/SimpleApiDocBundle/blob/master/src/Parser/ValidationParser.php
[8]: https://github.com/kreta/SimpleApiDocBundle/blob/master/src/Extractor/ApiDocExtractor.php
