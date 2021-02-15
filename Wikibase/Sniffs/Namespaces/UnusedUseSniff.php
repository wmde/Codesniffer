<?php

namespace Wikibase\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Custom sniff that finds and removes "use" clauses that are neither used in code nor in
 * documentation.
 *
 * @since 0.2.0
 *
 * @license GPL-2.0-or-later
 * @author Thiemo Kreuz
 */
class UnusedUseSniff implements Sniff {

	public function register() {
		return [ T_USE ];
	}

	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		// Shorten out if not on the top level, required to properly detect the use of traits
		if ( !empty( $tokens[$stackPtr]['conditions'] ) ) {
			return;
		}

		$useEndPtr = $phpcsFile->findEndOfStatement( $stackPtr + 1 );
		$classNamePtr = $phpcsFile->findPrevious( T_STRING, $useEndPtr - 1, $stackPtr + 1 );
		if ( !$classNamePtr ) {
			return;
		}

		$className = $tokens[$classNamePtr]['content'];
		$docTagPattern = 'expectedException|param|return|see|throw|type|var';
		$varDocPattern = '(?:\S+\s+)?\S*\b' . preg_quote( $className, '/' ) . '\b/i';

		for ( $i = $useEndPtr + 1; $i < $phpcsFile->numTokens; $i++ ) {
			$token = $tokens[$i];

			if ( ( $token['code'] === T_RETURN_TYPE
					&& strcasecmp( $token['content'], $className ) === 0
				)
				|| ( $token['code'] === T_STRING
					&& $tokens[$i - 1]['code'] !== T_DOUBLE_COLON
					&& $tokens[$i - 1]['code'] !== T_OBJECT_OPERATOR
					&& strcasecmp( $token['content'], $className ) === 0
				)
				|| ( $token['code'] === T_DOC_COMMENT_TAG
					&& $tokens[$i + 2]['code'] === T_DOC_COMMENT_STRING
					&& preg_match( '/^@(?:' . $docTagPattern . ')/i', $token['content'] )
					&& preg_match( '/^' . $varDocPattern, $tokens[$i + 2]['content'] )
				)
				|| ( $token['code'] === T_COMMENT
					&& preg_match( '/@(?:type|var)\s+' . $varDocPattern, $token['content'] )
				)
			) {
				return;
			}
		}

		if ( !$phpcsFile->addFixableError(
			"Unused import of \"$className\" found",
			$stackPtr,
			'Found'
		) ) {
			return;
		}

		for ( $ptr = $stackPtr; $ptr <= $useEndPtr; $ptr++ ) {
			$phpcsFile->fixer->replaceToken( $ptr, '' );
		}
		if ( $tokens[$ptr]['code'] === T_WHITESPACE && $tokens[$ptr]['content'][0] === "\n" ) {
			$phpcsFile->fixer->substrToken( $ptr, 1 );
		}
	}

}
