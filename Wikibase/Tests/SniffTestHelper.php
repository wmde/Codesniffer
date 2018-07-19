<?php

namespace Wikibase\CodeSniffer\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\DummyFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Util\Common;

/**
 * phpcs:disable MediaWiki.Usage.ForbiddenFunctions.assert
 *
 * @license GPL-2.0-or-later
 */
class SniffTestHelper {

	/**
	 * @var string
	 */
	private $sniffClass;

	private $properties = [];

	public function __construct( $sniffClass, array $properties ) {
		assert( class_exists( $sniffClass ), "Class {$sniffClass} was not found" );
		$this->sniffClass = $sniffClass;
		$this->properties = $properties;
	}

	/**
	 * @return DummyFile
	 */
	public function createFileObject( $contents ) {

		$sniffCode = Common::getSniffCode( $this->sniffClass );

		$config = new Config( [], false );
		$config->sniffs = [ $sniffCode ];

		$ruleset = new Ruleset( $config );
		foreach ( $this->properties as $propertyName => $value ) {
			// Neet to set property both ways: for file->process() and for fixer to work
			$ruleset->setSniffProperty( $this->sniffClass, $propertyName, $value );
			$ruleset->ruleset[$sniffCode]['properties'][$propertyName] = $value;
		}

		return new DummyFile( $contents, $ruleset, $config );
	}

}
