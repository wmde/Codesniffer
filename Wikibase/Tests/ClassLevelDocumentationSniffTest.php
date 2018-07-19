<?php

namespace Wikibase\CodeSniffer\Tests;

use Wikibase\Sniffs\Commenting\ClassLevelDocumentationSniff;

/**
 * @covers \Wikibase\Sniffs\Commenting\ClassLevelDocumentationSniff
 *
 * @license GPL-2.0-or-later
 */
class ClassLevelDocumentationSniffTest extends \PHPUnit_Framework_TestCase {

	public function testAddsCorrectLicenseIfNonePresent() {
		$givenLicense = 'http://opensource.org/licenses/gpl-license.php GNU Public License';

		$noLicense = <<<PHP
<?php
/**
 * Just some text
 */
class A {}
PHP;

		$helper = new SniffTestHelper(
			ClassLevelDocumentationSniff::class,
			[ 'license' => $givenLicense ]
		);
		$phpCsFile = $helper->createFileObject( $noLicense );

		$phpCsFile->process();
		$phpCsFile->fixer->fixFile();
		$result = $phpCsFile->fixer->getContents();

		$withLicense = <<<PHP
<?php
/**
 * Just some text
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
class A {}
PHP;
		$this->assertEquals( $withLicense, $result );
	}

	public function testDoesntDoAnythingIfLicenseIsNotSet() {
		$noLicense = <<<PHP
<?php
/**
 * Just some text
 */
class A {}
PHP;

		$helper = new SniffTestHelper(
			ClassLevelDocumentationSniff::class,
			[ 'license' => '' ]
		);
		$phpCsFile = $helper->createFileObject( $noLicense );

		$phpCsFile->process();
		$phpCsFile->fixer->fixFile();
		$result = $phpCsFile->fixer->getContents();

		$this->assertEquals( $noLicense, $result );
	}

	public function testNoDocBlock_AddsDocBlockWithLicense() {
		$noLicense = <<<PHP
<?php
class A {}
PHP;

		$helper = new SniffTestHelper(
			ClassLevelDocumentationSniff::class,
			[ 'license' => 'GPLv2' ]
		);
		$phpCsFile = $helper->createFileObject( $noLicense );

		$phpCsFile->process();
		$phpCsFile->fixer->fixFile();
		$result = $phpCsFile->fixer->getContents();

		$withLicense = <<<PHP
<?php
/**
 * @license GPLv2
 */
class A {}
PHP;

		$this->assertEquals( $withLicense, $result );
	}

}
