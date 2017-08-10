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
		$tokens = $phpcsFile->getTokens();
		$previous = $phpcsFile->findPrevious( T_WHITESPACE, $stackPtr - 1, null, true );

		if ( $tokens[$previous]['code'] === T_DOC_COMMENT_CLOSE_TAG ) {
			if ( $previous !== $stackPtr - 2 || $tokens[$stackPtr - 1]['content'] !== "\n" ) {
				$phpcsFile->addWarning(
					'Unexpected whitespace after class level documentation',
					$stackPtr,
					'Whitespace'
				);
			}
		} elseif ( $tokens[$previous]['code'] === T_COMMENT ) {
			$phpcsFile->addError(
				'Regular comment found instead of class level documentation',
				$stackPtr,
				'Regular'
			);
		} else {
			$phpcsFile->addError( 'Class level documentation missing', $stackPtr, 'Missing' );
		}
	}

}
