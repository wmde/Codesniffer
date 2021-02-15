<?php

class Example {

	/**
	 * @var bool
	 */
	private $uncommented;

	/**
	 * @var bool This is just text and should not be reported as an error.
	 */
	private $validComment;

	/**
	 * @var bool $mentioningOtherVariables should be allowed in such a comment.
	 */
	private $someOtherVariableName;

	/**
	 * @var bool Also, $mentioning the same variable name later in the sentence should be allowed.
	 */
	private $mentioning;

	/**
	 * @var bool $notTheSameVariableName should be allowed.
	 */
	private $not;

	/**
	 * @Var bool $redundantVariableName
	 */
	private $redundantVariableName;

	/**
	 * @var bool  $redundantWithComment after the variable name.
	 */
	private $redundantWithComment;

	/**
	 * @var $redundantBeforeType bool
	 */
	private $redundantBeforeType;

	private function __construct() {
		/** @var bool $local */
		$local = true;
	}

}
