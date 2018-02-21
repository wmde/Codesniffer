<?php

/**
 * Custom sniff that checks if all "use" clauses are alphabetically ordered.
 *
 * @license GPL-2.0+
 * @author Thiemo Mättig
 */
class Wikibase_Sniffs_Namespaces_AlphabeticallyOrderedUseSniff implements PHP_CodeSniffer_Sniff {

	public function register() {
		return [ T_USE ];
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$nextPtr = $phpcsFile->findNext( T_USE, $stackPtr + 1 );
		if ( !$nextPtr ) {
			return;
		}

		$className1 = $this->getFullQualifiedClassName( $phpcsFile, $stackPtr + 1 );
		$className2 = $this->getFullQualifiedClassName( $phpcsFile, $nextPtr + 1 );
		if ( !$className1 || !$className2 || strcasecmp( $className1, $className2 ) < 0 ) {
			return;
		}

		if ( $phpcsFile->addFixableError(
			'Imports are not alphabetically ordered',
			$nextPtr,
			'Found'
		) ) {
			// TODO
		}
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $usePtr
	 *
	 * @return string|null
	 */
	private function getFullQualifiedClassName( PHP_CodeSniffer_File $phpcsFile, $usePtr ) {
		$tokens = $phpcsFile->getTokens();
		$className = '';

		for ( $i = $usePtr; $i < $phpcsFile->numTokens; $i++ ) {
			switch ( $tokens[$i]['code'] ) {
				case T_AS:
				case T_NS_SEPARATOR:
				case T_STRING:
				case T_WHITESPACE:
					$className .= $tokens[$i]['content'];
					break;
				case T_SEMICOLON:
					return $className;
				default:
					return null;
			}
		}

		return null;
	}

}
