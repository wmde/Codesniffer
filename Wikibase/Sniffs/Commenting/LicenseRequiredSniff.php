<?php

namespace Wikibase\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * @since 1.0
 *
 * @license GPL-2.0-or-later
 */
class LicenseRequiredSniff implements Sniff {

	public $license = 'GPL-2.0-or-later'; // TODO: what would be the good default?

	public function process( File $phpcsFile, $stackPtr ) {
		$commentEndPosition = $phpcsFile->findPrevious( [
			T_ABSTRACT,
			T_FINAL,
			T_WHITESPACE,
		], $stackPtr - 1, null, true );

		$tokens = $phpcsFile->getTokens();
		if ( $tokens[$commentEndPosition]['code'] !== T_DOC_COMMENT_CLOSE_TAG ) {
			// TODO: make fixable
			$phpcsFile->addError( 'Class level documentation missing', $stackPtr, 'LicenseCommentMissing' );
			return;
		}

		$commentStartPosition = $phpcsFile->findPrevious(
			T_DOC_COMMENT_OPEN_TAG,
			$commentEndPosition - 1
		);

		$licenseFound = false;
		foreach ( $tokens[$commentStartPosition]['comment_tags'] as $tag ) {
			if ( $this->docTagContainsLicense( $phpcsFile, $tokens, $tag, $commentEndPosition ) ) {
				$licenseFound = true;
				break;
			}
		}

		if ( !$licenseFound ) {
			// TODO: make fixable
			$phpcsFile->addError(
				'No @license tag: ' . $this->license . ' found',
				$stackPtr,
				'LicenseNotFoundInFile'
			);
		}
	}

	private function docTagContainsLicense( File $phpcsFile, array $tokens, $tag, $end ) {
		$tagText = $tokens[$tag]['content'];

		if ( $tagText !== '@license' ) {
			return false;
		}

		if ( $tokens[$tag]['level'] !== 0 ) {
			return false;
		}

		$next = $phpcsFile->findNext( [ T_DOC_COMMENT_WHITESPACE ], $tag + 1, $end, true );
		if ( $tokens[$next]['code'] !== T_DOC_COMMENT_STRING ) {
			// No license after @license
			return false;
		}
		$license = $tokens[$next]['content'];

		// @license can contain a url, use the text behind it
		$m = [];
		if ( preg_match( '/^https?:\/\/[^\s]+\s+(.*)/', $license, $m ) ) {
			$license = $m[1];
		}

		return $license === $this->license;
	}

	public function register() {
		return [
			T_CLASS,
			T_INTERFACE,
			T_TRAIT,
		];
	}

}
