<?php

/**
 * Custom sniff that reports deprecated PHPDoc tags (e.g. known misspellings) and replaces them with
 * their preferred counterparts.
 *
 * @license GPL-2.0+
 * @author Thiemo Mättig
 */
class Wikibase_Sniffs_Commenting_DisallowedDocTagsSniff implements PHP_CodeSniffer_Sniff {

	/**
	 * @var array Array mapping deprecated to preferred PHPDoc tags. If an array key is all
	 *  lowercase, a case-insensitive search will be done. Set a value to null if a tag is
	 *  forbidden, but no automatic fix should be made.
	 */
	public $disallowedTags = [
		// https://docs.phpdoc.org/references/phpdoc/tags/index.html
		'@licence' => '@license',
		'@params' => '@param',
		'@returns' => '@return',
		'@throw' => '@throws',

		// https://docs.phpdoc.org/guides/inheritance.html
		'@inheritdoc' => '@inheritDoc',

		// https://github.com/phpDocumentor/fig-standards/pull/55
		'@type' => '@var',

		// https://phpunit.de/manual/current/en/appendixes.annotations.html#appendixes.annotations.covers
		'@cover' => '@covers',
		'@use' => '@uses',
	];

	public function register() {
		return [ T_DOC_COMMENT_TAG ];
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tag = $phpcsFile->getTokensAsString( $stackPtr, 1 );
		$replacement = false;

		if ( array_key_exists( $tag, $this->disallowedTags ) ) {
			$replacement = $this->disallowedTags[$tag];
		} elseif ( array_key_exists( strtolower( $tag ), $this->disallowedTags ) ) {
			$replacement = $this->disallowedTags[strtolower( $tag )];
		}

		if ( $replacement !== false && $replacement !== $tag ) {
			if ( !is_string( $replacement ) ) {
				$phpcsFile->addError( "PHPDoc tag $tag is not allowed", $stackPtr, 'Disallowed' );
			} elseif ( $phpcsFile->addFixableError(
				"Found $tag instead of $replacement",
				$stackPtr,
				'Found'
			) ) {
				$phpcsFile->fixer->replaceToken( $stackPtr, $replacement );
			}
		}
	}

}
