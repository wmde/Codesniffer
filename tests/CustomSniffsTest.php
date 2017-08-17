<?php

namespace Wikibase\CodeSniffer\Tests;

use PHP_CodeSniffer_CLI;
use PHPUnit_Framework_TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Test runner for custom Wikibase CodeSniffer sniffs. This is copied from the MediaWiki CodeSniffer
 * code repository, but simplified a lot.
 *
 * @license GPL-2.0+
 * @author Thiemo Mättig
 */
class CustomSniffsTest extends PHPUnit_Framework_TestCase {

	public static function provideTestCases() {
		$tests = [];
		$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( __DIR__ ) );

		/** @var SplFileInfo $file */
		foreach ( $iterator as $file ) {
			if ( !$file->isFile()
				|| $file->getExtension() !== 'php'
				|| $file->getPath() === __DIR__
			) {
				continue;
			}

			$shortName = $file->getBasename( '.' . $file->getExtension() );
			$standard = basename( dirname( $file->getPath() ) );
			$sniff = $standard . '.' . basename( $file->getPath() ) . '.' . $shortName;

			$tests[$shortName] = [
				dirname( __DIR__ ) . DIRECTORY_SEPARATOR . $standard,
				$sniff,
				$file->getPathname()
			];
		}

		return $tests;
	}

	/**
	 * @dataProvider provideTestCases
	 */
	public function testCodeSnifferStandardFiles( $standard, $sniff, $file ) {
		$phpcs = new PHP_CodeSniffer_CLI();
		$options = [
			'standard' => $standard,
			'sniffs' => [ $sniff ],
			'files' => [ $file ],
			'reportWidth' => 140,
		];

		ob_start();
		$phpcs->process( $options );
		$actual = ob_get_clean();

		$actual = preg_replace( '/^.*--\n(?= )/s', '', $actual );
		$actual = preg_replace( '/^--+\n\n.*/ms', '', $actual );

		$expectedFile = $file . '.expected';
		if ( !file_exists( $expectedFile ) ) {
			file_put_contents( $expectedFile, $actual );
		}

		$expected = file_get_contents( $expectedFile );
		$this->assertSame( $expected, $actual );
	}

}
