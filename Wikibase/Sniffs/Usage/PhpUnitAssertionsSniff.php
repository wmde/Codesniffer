<?php

/**
 * Custom sniff that finds ….
 *
 * @since 0.2.0
 *
 * @license GPL-2.0+
 * @author Thiemo Mättig
 */
class Wikibase_Sniffs_Usage_PhpUnitAssertionsSniff implements PHP_CodeSniffer_Sniff {

	public function register() {
		return [ T_STRING ];
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		switch ( $tokens[$stackPtr]['content'] ) {
			case 'assertTrue':
				break;
			default:
				return;
		}
	}

}
