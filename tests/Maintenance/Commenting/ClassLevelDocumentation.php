<?php

/**
 * This should not be reported as an error, but everything below should.
 */
class MyCompliantClass {
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

class ToMuchWhiteSpace {
}

/*
 * This is not a PHPDoc comment.
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
}
