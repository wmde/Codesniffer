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
 */
class WikibaseStandardTest extends PHPUnit_Framework_TestCase {

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
			$sniff = 'Wikibase.' . basename( $file->getPath() ) . '.' . $shortName;

			$tests[$shortName] = [ $sniff, $file->getPathname() ];
		}

		return $tests;
	}

	/**
	 * @dataProvider provideTestCases
	 */
	public function testCodeSnifferStandardFiles( $sniff, $file ) {
		$phpcs = new PHP_CodeSniffer_CLI();
		$options = [
			'standard' => __DIR__ . '/..',
			'sniffs' => [ $sniff ],
			'files' => [ $file ],
			'reportWidth' => 120,
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
