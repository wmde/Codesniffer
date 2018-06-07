<?php

// phpcs:property Wikibase\Sniffs\Commenting\ClassLevelDocumentationSniff::$license = MIT
// phpcs:disable Generic.Files.OneObjectStructurePerFile

class Undocumented {
}

/**
 * @todo Add license.
 */
class Unlicensed {
}

/**
 * @license LGPL-3.0-or-later with unwanted extra text
 */
class WrongLicense {
}

/**
 * @license LGPL-3.0-or-later
 * @licence MIT
 */
class MultiLicensed {
}

/**
 * @inheritDoc
 */class MisplacedEmptyComment {
}

/*
 * @license LGPL-3.0-or-later
 */
class NonPhpDocComment {
}

namespace Nested {

	abstract class Undocumented {
	}

	/**
	 * @inheritDoc
	 */
	class EmptyComment {
	}

	/**
	 * Multiple sniffs detect this, but no other can fix it.
	 * @license
	 */
	class EmptyLicense {
	}

}
