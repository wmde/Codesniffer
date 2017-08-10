<?php

/**
 * Custom sniff that checks for usages of "@returns" with an "s" instead of the preferred "@return".
 */
class Wikibase_Sniffs_Commenting_ReturnsTagSniff implements PHP_CodeSniffer_Sniff {

	public function register() {
		return [ T_FUNCTION ];
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$commentClose = $phpcsFile->findPrevious( T_WHITESPACE, $stackPtr - 1, null, true );

		if ( $tokens[$commentClose]['code'] !== T_DOC_COMMENT_CLOSE_TAG ) {
			return;
		}

		$commentOpen = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $commentClose - 1 );
		for ( $i = $commentOpen + 1; $i < $commentClose; $i++ ) {
			if ( $tokens[$i]['code'] === T_DOC_COMMENT_TAG
				&& strtolower( $tokens[$i]['content'] ) === '@returns'
			) {
				$phpcsFile->addError( 'Found @returns instead of @return', $stackPtr, 'Returns' );
				return;
			}
		}
	}

}
