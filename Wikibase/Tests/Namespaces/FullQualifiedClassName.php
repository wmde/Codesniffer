<?php

namespace OwnNamespace\Deeper;

use SomeNamespace\Example1;
use /* Comment */ /** @inheritDoc */ SomeNamespace\Deeper\Example2;
use \SomeNamespace\Example3 as Example4;

/**
 * @covers \SomeNamespace\Example1
 */
class AllowSingleBackslash extends \PHPUnit_Framework_TestCase {

	/**
	 * @param Example1 $a
	 * @param SomeNamespace\Example1 $b Class names in PHPDoc comments are currently not checked.
	 * @return \SomeNamespace\Example1
	 */
	public function allowFunctions() {
		\Wikimedia\suppressWarnings();
	}

}

class AllowClassNameOnly extends Example4 {
}

class DisallowedFullQualifiedClassName extends SomeNamespace\Example1 {
}

class DisallowedFullQualifiedClassNameWithBackslash extends \SomeNamespace\Example1 {
}

class AllowPHPUnitFrameworkTestCaseTest extends PHPUnit\Framework\TestCase {
}

class AllowPHPUnitFrameworkTestCaseWithBackslashTest extends \PHPUnit\Framework\TestCase {
}
