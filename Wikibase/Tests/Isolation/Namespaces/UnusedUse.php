<?php

use Sub\Unused;
use UsedButBadCapitalization;
use Wikibase\AnotherBadCapitalization;
use UnusedMain ;
use UsedMain ;
use AnotherUnusedMain;
use Sub\Used;
use Wikibase\Sub\UsedInAnArray;
use UsedAndCommented;
use MentionedInComment;
use AppearsToBeUnused as ButIsUsed;
use Sub\AnotherAlias as /* Comments should be skipped */WithAComment;
use Sub\AndAnotherAlias as /** @note PHPDocs should also be skipped */WithADocComment;
use UsedAfterParamName;
use UsedAfterVarName;
use UsedAfterPipe;
use UsedTrait;
use UsedExpectedException;
use UsedThrows;
use UsedReturn;
use UsedPhp7ReturnType;

/**
 * @group Unused
 */
class Example implements UsedMain {

	use UsedTrait;

	/**
	 * @type Used
	 */
	private $prop;

	/**
	 * @var UsedAndCommented with a comment
	 */
	private $prop2;

	/**
	 * @var UsedButBadCapiTaliZation
	 */
	private $prop3;

	/**
	 * @expectedException UsedExpectedException
	 * @param int[]|UsedInAnArray[] $arg
	 * @param bool $arg MentionedInComment should not count as usage
	 * @param $arg UsedAfterParamName[]|null
	 * @throws UsedThrows
	 * @return UsedReturn
	 */
	private function process( array $arg, AnotherBadCapiTaliZation $arg ) {
		/* @var ButIsUsed $var */
		/* @var $var UsedAfterVarName[]|null */
		/* @var $var null|UsedAfterPipe */
		/* @var $var bool MentionedInComment should not count as usage */
		$withAComment = new WithAComment();
		$withADocComment = new WithADocComment();

		// These "unused" are strings for the tokenizer, but not class names
		PrecededByDoubleColon::unused();
		$precededByObjectOperator->unused();
	}

	public function php7ReturnType(): UsedPhp7ReturnType {
	}

}
