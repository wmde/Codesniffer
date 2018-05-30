<?php

namespace Wikibase\CodeSniffer\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\DummyFile;
use PHP_CodeSniffer\Ruleset;
use Wikibase\Sniffs\Commenting\ClassLevelDocumentationSniff;

/**
 * @license GPL-2.0-or-later
 */
class ClassLevelDocumentationSniffTest extends \PHPUnit_Framework_TestCase {

	public function testAddsCorrectLicenseIfNonePresent() {
		$sniff = 'Wikibase.Commenting.ClassLevelDocumentation';
		$givenLicense = 'GPLv2';

		$noLicense = <<<PHP
<?php
/**
 * Just some text
 */
class A {}
PHP;

		$config = new Config( [], false );
		$config->sniffs = [ $sniff ];

		$ruleset = new Ruleset( $config );
		// Neet to set property both ways: for file->process() and for fixer to work
		$ruleset->setSniffProperty( ClassLevelDocumentationSniff::class, 'license', $givenLicense );
		$ruleset->ruleset[$sniff]['properties'] = [ 'license' => $givenLicense ];
		$phpCsFile = new DummyFile( $noLicense, $ruleset, $config );
		$phpCsFile->process();

		$phpCsFile->fixer->fixFile();
		$result = $phpCsFile->fixer->getContents();

		$withLicense = <<<PHP
<?php
/**
 * Just some text
 * @license GPLv2
 */
class A {}
PHP;
		$this->assertEquals( $withLicense, $result );
	}

}
