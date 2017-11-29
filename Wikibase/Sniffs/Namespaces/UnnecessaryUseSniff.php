<?php

/**
 * Custom sniff that checks and removes unnecessary "use" clauses that are in the same namespace as
 * the rest of the code.
 *
 * @since 0.2.0
 *
 * @license GPL-2.0+
 * @author Thiemo Kreuz
 */
class Wikibase_Sniffs_Namespaces_UnnecessaryUseSniff implements PHP_CodeSniffer_Sniff {

	public function register() {
		return [ T_USE ];
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$nsPtr = $phpcsFile->findPrevious( T_NAMESPACE, $stackPtr - 1 );
		if ( !$nsPtr ) {
			// Shorten out as fast as possible if no "namespace" could be found
			return;
		}

		$expectedUseEnd = $phpcsFile->numTokens - 1;
		for ( $usePtr = $stackPtr + 1; ; $usePtr++ ) {
			if ( $usePtr > $expectedUseEnd ) {
				return;
			}

			$nsElement = $tokens[++$nsPtr];
			$useElement = $tokens[$usePtr];

			if ( $useElement['code'] === T_SEMICOLON ) {
				if ( $usePtr === $expectedUseEnd
					&& $tokens[$nsPtr - 2]['code'] === T_SEMICOLON
					&& $tokens[$usePtr - 2]['code'] === T_NS_SEPARATOR
				) {
					// Successfully found a matching "use", continue after the loop
					break;
				}

				// Shorten out if the "use" is shorter than expected
				return;
			}

			if ( $nsElement['code'] !== $useElement['code']
				|| trim( $nsElement['content'] ) !== trim( $useElement['content'] )
			) {
				// The "use" is expected to be 2 tokens longer than the "namespace"
				$expectedUseEnd = min( $expectedUseEnd, $usePtr + 2 );
			}
		}

		$className = $tokens[$expectedUseEnd - 1]['content'];
		if ( !$phpcsFile->addFixableError(
			"Unnecessary import of \"$className\" from own namespace",
			$stackPtr,
			'Found'
		)
		) {
			return;
		}

		for ( $ptr = $stackPtr; $ptr <= $expectedUseEnd; $ptr++ ) {
			$phpcsFile->fixer->replaceToken( $ptr, '' );
		}
		if ( $tokens[$ptr]['code'] === T_WHITESPACE && $tokens[$ptr]['content'][0] === "\n" ) {
			// Remove only the one linebreak directly behind the removed "use"
			$phpcsFile->fixer->substrToken( $ptr, 1 );
		}
	}

}
