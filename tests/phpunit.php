<?php

require_once dirname(__DIR__) . '/src/BasicArrayFunctions/main.php';

class BasicArrayFunctionsTest extends PHPUnit_Framework_TestCase {

	/**
	 * array_patch function.
	 */
	Public function testArrayPatch()
	{
		$array = [
			"foo" => [
				"sub" => [
					"blabla" => 123
				]
			]
		];

		$patch = \array_patch("foo.sub.blabla", 123);

		$this->assertEquals($array, $patch);
	}

	/**
	 * array_del
	 */
	Public function testArrayDel()
	{
		$array = [
			"foo" => [
				"sub" => [
					"source" => "blabla"
				]
			]
		];

		\array_del($array, "foo.sub.source");

		$this->assertEquals(
			[
				"foo" => [
					"sub" => []
				]
			],
			$array
		);
	}

	/**
	 * array_get function.
	 */
	Public function testArrayGet()
	{
		$array = [
			"foo" => [
				"sub" => [
					"source" => "blabla"
				]
			]
		];

		$this->assertEquals("blabla", \array_get($array, "foo.sub.source"));

		$this->assertEquals("newValue", \array_get($array, "foo.sub.new", "newValue"));
	}

	/**
	 * array_set function.
	 */
	Public function testArraySet()
	{
		$array = [
			"foo" => [
				"sub" => [
					"blabla" => 123
				]
			]
		];

		$this->assertEquals(
			   [
				"foo" => [
					"sub" => [
						"blabla" => 222
					]
				]
			   ],
			\array_set($array, "foo.sub.blabla", 222)
		);

		$this->assertEquals(
			[
				"foo" => [
					"sub" => [
						"blabla" => 123
					],
					"new" => [
						"key" => "shhh"
					]
				]
			],
			\array_set($array, "foo.new.key", "shhh")
		);

	}

	/**
	 * array_key_joiner function.
	 */
	Public function testArrayKeyJoiner()
	{
		$array = [
			"foo" => [
				"sub" => [
					"blabla" => 123
				]
			],
			"dıptıss" => [
				"vuhu" => "uuuu"
			]
		];

		$patch = [
			"foo" => [
				"sub" => "vuvvvuvvv"
			]
		];

		$this->assertEquals(
			[
				"foo" => [
					"sub" => "vuvvvuvvv"
				],
				"dıptıss" => [
					"vuhu" => "uuuu"
				]
			],
			\array_key_joiner($array, $patch)
		);
	}

	/**
	 * array_val_joiner function.
	 */
	Public function testArrayValJoiner()
	{
		$array = [
			"foo" => [1, 2, 3],
			"bar" => 123,
			"some" => "any"
		];

		$patch = [
			"vuv" => 123,
			"some" => "newSome",
			"array" => [1, 2, 3]
		];

		$this->assertEquals(
			[
				"array" => [1, 2, 3],
				"vuv" => 123,
				"some" => "newSome",
				"any"
			],
			\array_val_joiner($array, $patch)
		);
	}

	/**
	 * array_parent_path function.
	 */
	Public function testArrayParentPath()
	{
		$path = "foo.sub.child";

		$this->assertEquals("foo.sub", \array_parent_path($path));
	}

	/**
	 * array_key_joiner_recursive function.
	 */
	Public function testArrayKeyJoinerRecursive()
	{
		$array = [
			"foo" => [
				"sub" => [
					"vuu" => "uuuuuv"
				],
				"bla" => [
					"ss" => "ssss"
				]
			]
		];

		$patch =  [
			"newFoo" => [
				"pff" => "fff"
			],
			"foo" => [
				"sub" => "noSub!",
				"bla" => [
					"longs" => "ssssssssssssss"
				]
			]
		];

		$this->assertEquals(
			[
				"newFoo" => [
					"pff" => "fff"
				],
				"foo" => [
					"sub" => "noSub!",
					"bla" => [
						"longs" => "ssssssssssssss",
						"ss" => "ssss"
					]
				]
			],
			\array_key_joiner_recursive($array, $patch)
		);
	}
}