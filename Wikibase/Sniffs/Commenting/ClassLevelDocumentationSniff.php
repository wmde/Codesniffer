<?php

namespace Wikibase\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Custom sniff that reports classes, interfaces, and traits that are not directly preceded by a
 * non-empty documentation comment. Comments with empty PHPDoc tags like "@inheritDoc" are still
 * considered empty.
 *
 * @since 0.2.0
 *
 * @license GPL-2.0+
 * @author Thiemo Kreuz
 */
class ClassLevelDocumentationSniff implements Sniff {

	public function register() {
		return [
			T_CLASS,
			T_INTERFACE,
			T_TRAIT,
		];
	}

	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$previous = $phpcsFile->findPrevious( [
			T_ABSTRACT,
			T_FINAL,
			T_WHITESPACE,
		], $stackPtr - 1, null, true );

		if ( $tokens[$previous]['code'] === T_COMMENT
			&& substr( $tokens[$previous]['content'], -2 ) === '*/'
		) {
			if ( $phpcsFile->addFixableError(
				'Regular comment found instead of class level documentation',
				$previous,
				'Regular'
			) ) {
				do {
					if ( substr( $tokens[$previous]['content'], 0, 2 ) === '/*' ) {
						$phpcsFile->fixer->replaceToken(
							$previous,
							substr_replace( $tokens[$previous]['content'], '*', 2, 0 )
						);
						break;
					}

					$previous--;
				} while ( $tokens[$previous]['code'] === T_COMMENT );
			}

			return;
		} elseif ( $tokens[$previous]['code'] !== T_DOC_COMMENT_CLOSE_TAG ) {
			$phpcsFile->addError( 'Class level documentation missing', $stackPtr, 'Missing' );
			return;
		}

		$newlines = substr_count(
			$phpcsFile->getTokensAsString( $previous + 1, $stackPtr - $previous - 1 ),
			"\n"
		);
		if ( !$newlines ) {
			// Recreating the correct indention is hard, that's why this is not fixable here
			$phpcsFile->addWarning(
				'No newline after class level documentation',
				$stackPtr - 1,
				'MissingNewline'
			);
		} elseif ( $newlines > 1 ) {
			if ( $phpcsFile->addFixableWarning(
				'To many newlines after class level documentation',
				$previous + 2,
				'ToManyNewlines'
			) ) {
				for ( $clean = false, $i = $stackPtr - 1; $i > $previous; $i-- ) {
					if ( $clean ) {
						$phpcsFile->fixer->replaceToken( $i, '' );
					} elseif ( strpos( $tokens[$i]['content'], "\n" ) !== false ) {
						$clean = true;
					}
				}
			}
		}

		$docStart = $phpcsFile->findPrevious( T_DOC_COMMENT_OPEN_TAG, $previous - 1 );
		if ( !$phpcsFile->findNext( T_DOC_COMMENT_STRING, $docStart + 1, $previous ) ) {
			$phpcsFile->addError(
				'Class level documentation is empty',
				$docStart,
				'Empty'
			);
		}
	}

}
