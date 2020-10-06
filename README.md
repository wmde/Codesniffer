Wikibase CodeSniffer standards
==============================

Abstract
--------
This project implements a set of rules for use with
[PHP CodeSniffer](https://packagist.org/packages/squizlabs/php_codesniffer).

See [Wikibase coding conventions](https://www.mediawiki.org/wiki/Wikibase/Coding_conventions) on the
MediaWiki wiki for an overview of the basic coding conventions that are validated by these rules.
Detailed comments explaining individual rules can be found in the Wikibase/ruleset.xml file in
this project.

This project aims to be less draconic than the
[MediaWiki CodeSniffer](https://github.com/wikimedia/mediawiki-tools-codesniffer) it is based on,
especially on comments in your code. We believe the primary goal of an automatic code sniffer is to
reduce pain in code reviews by guaranteeing a consistent, easy to read code style. When dealing with
the consequences of a rule is more painful than what it is able to provide (e.g. some extra
whitespace typically doesn't make code harder to read), we opt-out.

If you experience pain because of a rule you feel does not make your code easier to read, please
feel free to exclude it via `<exclude name="…" />` in your local .phpcs.xml, or
[suggest](https://github.com/wmde/WikibaseCodeSniffer/issues) to opt-out of this rule entirely.

How to install
--------------
1. Create a composer.json which adds this project as a dependency:

    ```
    {
    	"require-dev": {
    		"wikibase/wikibase-codesniffer": "^1.2.0"
    	},
    	"scripts": {
    		"test": [
    			"phpcs -p -s"
    		],
    		"fix": "phpcbf"
    	}
    }
    ```
2. Create a .phpcs.xml with our configuration:

    ```
    <?xml version="1.0"?>
    <ruleset>
    	<rule ref="./vendor/wikibase/wikibase-codesniffer/Wikibase"/>

    	<file>.</file>
    </ruleset>
    ```
3. Install: `composer update`
4. Run: `composer test`
5. Run: `composer fix` to auto-fix some of the errors, others might need
   manual intervention.
6. Commit!
