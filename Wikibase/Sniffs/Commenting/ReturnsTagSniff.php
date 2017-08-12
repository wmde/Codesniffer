<?php

/**
 * Custom sniff that checks for usages of "@returns" with an "s" instead of the preferred "@return".
 *
 * @license GPL-2.0+
 * @author Thiemo Mättig
 */
class Wikibase_Sniffs_Commenting_ReturnsTagSniff implements PHP_CodeSniffer_Sniff {

	public function register() {
		return [ T_DOC_COMMENT_TAG ];
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		if ( strtolower( $tokens[$stackPtr]['content'] ) === '@returns' ) {
			$phpcsFile->addError( 'Found @returns instead of @return', $stackPtr, 'Returns' );
		}
	}

}
