<?php

/**
 * This should not be reported as an error.
 */
class DocumentedClass {
}

/**
 * This should not be reported as an error.
 */
abstract class DocumentedAbstractClass {
}

/**
 * Missing newline between comment and class.
 */class MissingNewline {
}

/**
 * Wrong whitespace character between comment and class.
 */ class WrongWhitespace {
}

/**
 * To much whitespace between comment and class.
 */

abstract class ToManyNewlines {
}

/*
 * This is not a PHPDoc comment but can be turned into one.
 */
class NotAPhpDocComment {
}

interface UndocumentedInterface {
}

class UndocumentedClass {
}

trait UndocumentedTrait {
}

class UndocumentedImplementation implements UndocumentedInterface {
}

class UndocumentedSubClass extends UndocumentedClass {
}

namespace UndocumentedNamespace {
	class UndocumentedClassInNamespace {
	}
	/** Doc */class MissingNewlineInNamespace {
	}
	/** Doc */

	class ToManyNewlinesInNamespace {
	}
	/** Doc */
	class DocumentedClassInNamespace {
	}
	// This should not be turned into a PHPDoc comment
	class LineCommentInNamespace {
	}
}

/**
 * @inheritDoc
 */
class DocTagsButNoContent {
}
