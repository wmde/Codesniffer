<?php

/**
 * Custom sniff …
 */
class Wikibase_Sniffs_Commenting_RedundantVarNameSniff implements PHP_CodeSniffer_Sniff {

	public function register() {
		return [
			T_VARIABLE,
		];
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		if ( $tokens[$stackPtr]['level'] !== 1 ) {
			return;
		}

		$variableName = $tokens[$stackPtr]['content'];

		$commentClose = $phpcsFile->findPrevious(
			[
				T_PRIVATE,
				T_PROTECTED,
				T_PUBLIC,
				T_WHITESPACE,
			],
			$stackPtr - 1,
			null,
			true
		);

		if ( $tokens[$commentClose]['code'] !== T_DOC_COMMENT_CLOSE_TAG ) {
			return;
		}

		$commentOpen = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $commentClose - 1 );
		for ( $i = $commentOpen + 1; $i < $commentClose; $i++ ) {
			if ( $tokens[$i]['code'] === T_DOC_COMMENT_TAG
				&& strtolower( $tokens[$i]['content'] ) === '@var'
				&& preg_match(
					'/^(\S+\s+)?' . preg_quote( $variableName, '/' ) . '\b/is',
					$tokens[$i + 2]['content']
				)
			) {
				$phpcsFile->addError(
					'Found redundant variable name in @var',
					$stackPtr,
					'Redundant'
				);
				return;
			}
		}
	}

}
