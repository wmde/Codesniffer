<?php

namespace Wikibase\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Custom sniff that reports classes, interfaces, and traits that are not directly preceded by a
 * non-empty documentation comment. Comments with empty PHPDoc tags like "@inheritDoc" are still
 * considered empty.
 *
 * @since 0.2.0
 *
 * @license GPL-2.0-or-later
 * @author Thiemo Kreuz
 */
class ClassLevelDocumentationSniff implements Sniff {

	/**
	 * @var string
	 */
	public $license = '';

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
			if ( !$this->license ) {
				$phpcsFile->addError( 'Class level documentation missing', $stackPtr, 'Missing' );
			} elseif ( $phpcsFile->addFixableError( '@license missing', $stackPtr, 'LicenseMissing' ) ) {
				$this->addLicenseComment( $phpcsFile, $previous + 1 );
			}
			return;
		}

		$newlines = substr_count(
			$phpcsFile->getTokensAsString( $previous + 1, $stackPtr - $previous - 1 ),
			"\n"
		);
		if ( !$newlines ) {
			if ( $phpcsFile->addFixableWarning(
				'No newline after class level documentation',
				$stackPtr - 1,
				'MissingNewline'
			) ) {
				// This fix intentionally does not try to be too clever about non-tab indentions
				$phpcsFile->fixer->addContent(
					$previous,
					$phpcsFile->eolChar . str_repeat( "\t", $tokens[$stackPtr]['level'] )
				);
			}
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

		$docStart = $tokens[$previous]['comment_opener'];

		if ( $this->license ) {
			$this->fixLicense( $phpcsFile, $docStart, $previous );
		} elseif ( !$phpcsFile->findNext( T_DOC_COMMENT_STRING, $docStart + 1, $previous ) ) {
			$phpcsFile->addError(
				'Class level documentation is empty',
				$docStart,
				'Empty'
			);
		}
	}

	/**
	 * @param File $phpcsFile
	 * @param int $position
	 */
	private function addLicenseComment( File $phpcsFile, $position ) {
		$tokens = $phpcsFile->getTokens();

		// Skip empty tags in case $position did not pointed to the exact position
		$position = $phpcsFile->findNext( Tokens::$emptyTokens, $position, null, true );
		$indention = str_repeat( "\t", $tokens[$position]['level'] );
		$phpcsFile->fixer->addContentBefore(
			$position,
			"/**\n$indention * @license $this->license\n$indention */\n$indention"
		);
	}

	/**
	 * @param File $phpcsFile
	 * @param int $docStart
	 * @param int $docEnd
	 */
	private function fixLicense( File $phpcsFile, $docStart, $docEnd ) {
		$tokens = $phpcsFile->getTokens();

		// Intentionally scan bottom-up because @license tags are typically at the end
		foreach ( array_reverse( $tokens[$docStart]['comment_tags'] ) as $i ) {
			if ( !preg_match( '/^@licen[cs]e$/i', $tokens[$i]['content'] ) ) {
				continue;
			}

			if ( $tokens[$i + 1]['code'] !== T_DOC_COMMENT_WHITESPACE
				|| $tokens[$i + 2]['code'] !== T_DOC_COMMENT_STRING
			) {
				if ( $phpcsFile->addFixableError( '@license not followed by a license', $i, 'LicenseEmpty' ) ) {
					$phpcsFile->fixer->addContent( $i, " $this->license" );
				}
			} elseif ( strcasecmp( $tokens[$i + 2]['content'], $this->license ) !== 0
				&& $phpcsFile->addFixableError( 'Wrong @license', $i, 'LicenseWrong' )
			) {
				$phpcsFile->fixer->replaceToken( $i + 2, $this->license );
			}

			// Found (and fixed) an existing @license tag, don't add another one
			return;
		}

		if ( $phpcsFile->addFixableError( '@license missing', $docStart, 'LicenseMissing' ) ) {
			$indention = str_repeat( "\t", $tokens[$docEnd]['level'] );
			$phpcsFile->fixer->addContent( $docEnd - 1, "* @license $this->license\n$indention " );
		}
	}

}
