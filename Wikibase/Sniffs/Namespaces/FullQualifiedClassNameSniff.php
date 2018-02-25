<?php

namespace Wikibase\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Custom sniff that disallows full qualified class names outside of the "use" section.
 *
 * Class names in the main namespace (e.g. "\Title") are intentionally allowed for several reasons:
 * - The class name is still recognizable with a single backslash in front.
 * - A "use" takes more space than a dozen backslashes.
 * - This reflects the actual living code style in the Wikibase code bases that contain hundreds of
 *   "\MediaWikiTestCase", "\InvalidArgumentException", "\FauxRequest", and such.
 *
 * This sniff currently does not check class names mentioned in PHPDoc comments.
 *
 * @since 0.4.0
 *
 * @license GPL-2.0-or-later
 * @author Thiemo Kreuz
 */
class FullQualifiedClassNameSniff implements Sniff {

	public function register() {
		return [ T_NS_SEPARATOR ];
	}

	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		// Shorten out if:
		// - There is no namespace before the backslash. This intentionally allows "\Bar".
		// - There is no class name after the backslash.
		// - This backslash is not the last one before the class name.
		// - We are not dealing with a class name but a function.
		if ( $tokens[$stackPtr - 1]['code'] !== T_STRING
			|| $tokens[$stackPtr + 1]['code'] !== T_STRING
			|| $tokens[$stackPtr + 2]['code'] === T_NS_SEPARATOR
			|| $tokens[$stackPtr + 2]['code'] === T_OPEN_PARENTHESIS
		) {
			return;
		}

		$prev = $phpcsFile->findStartOfStatement( $stackPtr - 1 );
		if ( $tokens[$prev]['code'] === T_NAMESPACE
			|| $tokens[$prev]['code'] === T_USE
		) {
			return;
		}

		$phpcsFile->addError(
			'Full qualified class name "…\\%s" found, please utilize "use"',
			$stackPtr,
			'Found',
			[ $tokens[$stackPtr + 1]['content'] ]
		);
	}

}
