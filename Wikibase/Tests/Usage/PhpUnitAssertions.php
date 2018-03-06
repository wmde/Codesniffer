<?php

class Test extends \PHPUnit_Framework_Assert {

	public function test() {
		$this->assertArrayHasKey( $key, $array );
		$this->assertTrue( array_key_exists( $key, $array ) );
		$this->assertTrue( isset( $array[$key] ) );

		$this->assertArraySubset( $subset, $array, $strict );

		$this->assertArrayNotHasKey( $key, $array );
		$this->assertFalse( array_key_exists( $key, $array ) );
		$this->assertFalse( isset( $array[$key] ) );

		$this->assertContains( $needle, $haystack, $message, $ignoreCase, $checkForObjectIdentity, $checkForNonObjectIdentity );
		$this->assertTrue( in_array( $needle, $haystack ) );

		$this->assertNotContains( $needle, $haystack, $message, $ignoreCase, $checkForObjectIdentity, $checkForNonObjectIdentity );
		$this->assertFalse( in_array( $needle, $haystack ) );

		$this->assertContainsOnly( $type, $haystack, $isNativeType );

		$this->assertContainsOnlyInstancesOf( $classname, $haystack );

		$this->assertNotContainsOnly( $type, $haystack, $isNativeType );

		$this->assertCount( $expectedCount, $haystack );
		$this->assertEquals( $expectedCount, count( $haystack ) );
		$this->assertSame( $expectedCount, count( $haystack ) );
		$this->assertTrue( count( $haystack ) === $expectedCount );

		$this->assertNotCount( $expectedCount, $haystack );
		$this->assertNotEquals( $expectedCount, count( $haystack ) );
		$this->assertNotSame( $expectedCount, count( $haystack ) );
		$this->assertFalse( count( $haystack ) === $expectedCount );
		$this->assertTrue( count( $haystack ) !== $expectedCount );

		$this->assertEquals( $expected, $actual, $message, $delta, $maxDepth, $canonicalize, $ignoreCase );

		$this->assertNotEquals( $expected, $actual, $message, $delta, $maxDepth, $canonicalize, $ignoreCase );

		$this->assertEmpty( $actual );
		$this->assertTrue( empty( $actual ) );
		$this->assertEquals( 0, count( $actual ) );
		$this->assertSame( 0, count( $actual ) );

		$this->assertNotEmpty( $actual );
		$this->assertFalse( empty( $actual ) );
		$this->assertNotEquals( 0, count( $actual ) );
		$this->assertNotSame( 0, count( $actual ) );
		$this->assertGreaterThan( 0, count( $actual ) );

		$this->assertGreaterThan( $expected, $actual );

		$this->assertGreaterThanOrEqual( $expected, $actual );

		$this->assertLessThan( $expected, $actual );

		$this->assertLessThanOrEqual( $expected, $actual );

		$this->assertFileExists( $filename );
		$this->assertTrue( file_exists( $filename ) );

		$this->assertFileNotExists( $filename );
		$this->assertFalse( file_exists( $filename ) );

		$this->assertTrue( $condition );
		$this->assertEquals( true, $condition );
		$this->assertSame( true, $condition );

		$this->assertNotTrue( $condition );
		$this->assertNotEquals( true, $condition );
		$this->assertNotSame( true, $condition );

		$this->assertFalse( $condition );
		$this->assertEquals( false, $condition );
		$this->assertSame( false, $condition );

		$this->assertNotFalse( $condition );
		$this->assertNotEquals( false, $condition );
		$this->assertNotSame( false, $condition );

		$this->assertNotNull( $actual );
		$this->assertNotEquals( null, $condition );
		$this->assertNotSame( null, $condition );

		$this->assertNull( $actual );
		$this->assertEquals( null, $condition );
		$this->assertSame( null, $condition );

		$this->assertSame( $expected, $actual );

		$this->assertNotSame( $expected, $actual );

		$this->assertInstanceOf( $expected, $actual );
		$this->assertTrue( $actual instanceof $expected );

		$this->assertNotInstanceOf( $expected, $actual );
		$this->assertFalse( $actual instanceof $expected );

		$this->assertInternalType( $expected, $actual );

		$this->assertNotInternalType( $expected, $actual );

		$this->assertRegExp( $pattern, $string );
		$this->assertTrue( preg_match( $pattern, $string ) );

		$this->assertNotRegExp( $pattern, $string );
		$this->assertFalse( preg_match( $pattern, $string ) );

		$this->assertStringStartsWith( $prefix, $string );

		$this->assertStringStartsNotWith( $prefix, $string );

		$this->assertStringEndsWith( $suffix, $string );

		$this->assertStringEndsNotWith( $suffix, $string );

		$this->assertThat( $value, $constraint );
	}

}
