<?php

namespace Wikibase\CodeSniffer\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\DummyFile;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Reporter;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Util\Common;
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

	public function provideIntegrationTestCases() {
		foreach ( $this->scanPhpFiles( __DIR__ . '/Integration' ) as $file ) {
			yield $file->getFilename() => [ $file->getPathname() ];
		}
	}

	/**
	 * @dataProvider provideIntegrationTestCases
	 */
	public function testWikibaseStandard( $file ) {
		$expectedFile = $file . '.expected';
		$fixedFile = $file . '.fixed';

		$config = new Config();
		$config->standards = [ __DIR__ . '/..' ];
		$ruleset = new Ruleset( $config );

		$content = file_get_contents( $file );
		preg_match_all(
			'/\bphpcs:property +\\\\?([\w\\\\]+)::\$(\w+) *= *(.*)/',
			$content,
			$matches,
			PREG_SET_ORDER
		);
		foreach ( $matches as $match ) {
			list( , $sniffClass, $property, $value ) = $match;

			// Required for reporting
			$ruleset->setSniffProperty( $sniffClass, $property, $value );

			// Required for fixing
			$sniffCode = Common::getSniffCode( $sniffClass );
			$ruleset->ruleset[$sniffCode]['properties'][$property] = $value;
		}

		$phpCsFile = new DummyFile( $content, $ruleset, $config );
		$phpCsFile->process();

		// Optional here, as reporting is already covered by the isolated tests
		if ( file_exists( $expectedFile ) ) {
			$actual = $this->getPhpCbfReport( $phpCsFile );
			$this->assertSame( file_get_contents( $expectedFile ), $actual );
		}

		if ( file_exists( $fixedFile ) ) {
			$phpCsFile->fixer->fixFile();
			$actual = $phpCsFile->fixer->getContents();
			$this->assertSame( file_get_contents( $fixedFile ), $actual );
		}
	}

	public function provideIsolatedTestCases() {
		foreach ( $this->scanPhpFiles( __DIR__ . '/Isolation' ) as $file ) {
			$shortName = $file->getBasename( '.' . $file->getExtension() );
			$sniff = 'Wikibase.' . basename( $file->getPath() ) . '.' . $shortName;

			yield $shortName => [ $sniff, $file->getPathname() ];
		}
	}

	/**
	 * @dataProvider provideIsolatedTestCases
	 */
	public function testSniffsInIsolation( $sniff, $file ) {
		$expectedFile = $file . '.expected';
		$fixedFile = $file . '.fixed';

		$config = new Config();
		$config->standards = [ __DIR__ . '/..' ];
		// Test each sniff in isolation, in contrast to testing the whole standard
		$config->sniffs = [ $sniff ];
		$config->reportWidth = 140;

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
	 * @param string $path
	 *
	 * @return SplFileInfo[]
	 */
	private function scanPhpFiles( $path ) {
		$files = [];
		$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path ) );

		/** @var SplFileInfo $file */
		foreach ( $iterator as $file ) {
			if ( $file->isFile() && $file->getExtension() === 'php' ) {
				$files[] = $file;
			}
		}

		return $files;
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
