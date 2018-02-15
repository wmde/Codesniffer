<?php

namespace Wikibase\CodeSniffer\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\DummyFile;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Reporter;
use PHP_CodeSniffer\Ruleset;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Test runner for custom Wikibase CodeSniffer sniffs. This is copied from the MediaWiki CodeSniffer
 * code repository, but simplified a lot.
 *
 * @license GPL-2.0-or-later
 * @author Thiemo Kreuz
 */
class WikibaseStandardTest extends TestCase {

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

		$config = new Config();
		$config->standards = [ __DIR__ . '/..' ];
		$config->sniffs = [ $sniff ];

		$phpCsFile = new DummyFile( file_get_contents( $file ), new Ruleset( $config ), $config );
		$phpCsFile->process();
		$actual = $this->getPhpCbfReport( $phpCsFile );

		if ( !file_exists( $expectedFile ) ) {
			file_put_contents( $expectedFile, $actual );
		}

		$this->assertSame( file_get_contents( $expectedFile ), $actual );

		if ( file_exists( $fixedFile ) ) {
			$phpCsFile->fixer->fixFile();
			$actual = $phpCsFile->fixer->getContents();
			$this->assertSame( file_get_contents( $fixedFile ), $actual );
		}
	}

	/**
	 * @param File $phpCsFile
	 *
	 * @return string
	 */
	private function getPhpCbfReport( File $phpCsFile ) {
		$reporter = new Reporter( $phpCsFile->config );
		$reporter->cacheFileReport( $phpCsFile );

		ob_start();
		$reporter->printReport( 'full' );
		$output = ob_get_clean();

		// Remove header
		$output = preg_replace( '/^.*--\n(?= )/s', '', $output );
		// Remove footer, identified by a dashed line followed by a PHPCBF report or empty line
		$output = preg_replace( '/^--+\n(PHPCBF|\n).*/ms', '', $output );

		return $output;
	}

}
