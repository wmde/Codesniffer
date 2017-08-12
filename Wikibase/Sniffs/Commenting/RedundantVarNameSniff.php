<?php

/**
 * Custom sniff that checks if the "@var" documentation of a class property repeats the variable
 * name, which is unnecessary.
 *
 * @license GPL-2.0+
 * @author Thiemo Mättig
 */
class Wikibase_Sniffs_Commenting_RedundantVarNameSniff implements PHP_CodeSniffer_Sniff {

	public function register() {
		return [ T_DOC_COMMENT_TAG ];
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		if ( strtolower( $tokens[$stackPtr]['content'] ) !== '@var' ) {
			return;
		}

		$variablePtr = $phpcsFile->findNext( T_VARIABLE, $stackPtr + 1 );
		if ( $variablePtr === false ) {
			return;
		}
		$variableName = $tokens[$variablePtr]['content'];

		$visibilityPtr = $phpcsFile->findPrevious(
			T_WHITESPACE,
			$variablePtr - 1,
			$stackPtr + 1,
			true
		);
		if ( !in_array( $tokens[$visibilityPtr]['code'], [ T_PRIVATE, T_PROTECTED, T_PUBLIC ] ) ) {
			return;
		}

		$stringPtr = $phpcsFile->findNext(
			T_DOC_COMMENT_WHITESPACE,
			$stackPtr + 1,
			$visibilityPtr - 1,
			true
		);

		if ( $stringPtr !== false
			&& $tokens[$stringPtr]['code'] === T_DOC_COMMENT_STRING
			&& preg_match(
				'/^(\S+\s+)?' . preg_quote( $variableName, '/' ) . '\b/is',
				$tokens[$stringPtr]['content']
			)
		) {
			$phpcsFile->addError(
				'Found redundant variable name in @var',
				$stringPtr,
				'Redundant'
			);
		}
	}

}
