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
		$expectedFile = $file . '.expected';
		$fixedFile = $file . '.fixed';

		$options = [
			'standard' => __DIR__ . '/..',
			'sniffs' => [ $sniff ],
			'files' => [ $file ],
		];
		if ( file_exists( $fixedFile ) ) {
			$options += [
				'reports' => [ 'cbf' => null ],
				'phpcbf-suffix' => '.patched',
			];
		}

		$actual = $this->runPhpCs( $options );

		if ( !file_exists( $expectedFile ) ) {
			file_put_contents( $expectedFile, $actual );
		}

		$this->assertSame( file_get_contents( $expectedFile ), $actual );

		if ( isset( $options['phpcbf-suffix'] ) ) {
			$patchedFile = $file . $options['phpcbf-suffix'];
			$this->assertTrue( file_exists( $patchedFile ), 'Expected PHPCBF to apply fixes' );
			$actual = file_get_contents( $patchedFile );
			unlink( $patchedFile );
			$this->assertSame( file_get_contents( $fixedFile ), $actual );
		}
	}

	private function runPhpCs( array $options ) {
		$phpCs = new PHP_CodeSniffer_CLI();
		$options['reports']['full'] = null;
		$options['reportWidth'] = 140;

		ob_start();
		$phpCs->process( $options );
		$output = ob_get_clean();

		// Remove header
		$output = preg_replace( '/^.*--\n(?= )/s', '', $output );
		// Remove footer, identified by a dashed line followed by a PHPCBF report or empty line
		$output = preg_replace( '/^--+\n(PHPCBF|\n).*/ms', '', $output );

		return $output;
	}

}
