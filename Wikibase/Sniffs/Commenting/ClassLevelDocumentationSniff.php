<?php

/**
 * Custom sniff that reports classes, interfaces, and traits that are not directly preceded by a
 * documentation comment.
 */
class Wikibase_Sniffs_Commenting_ClassLevelDocumentationSniff implements PHP_CodeSniffer_Sniff {

	public function register() {
		return [
			T_CLASS,
			T_INTERFACE,
			T_TRAIT,
		];
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$comment = $phpcsFile->findPrevious( T_DOC_COMMENT_CLOSE_TAG, $stackPtr - 1, $stackPtr - 2 );

		if ( $comment === false ) {
			$phpcsFile->addError( 'Class level documentation missing', $stackPtr, 'Missing' );
		}
	}

}
