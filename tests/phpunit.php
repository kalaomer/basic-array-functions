<?php

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
}