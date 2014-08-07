<?php

namespace Nemex\Entity;

/**
 * @small
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provide_name
     */
    public function has_name($expectedName)
    {
        $expectedName = 'augustohp';
        $user = new User($expectedName);

        $this->assertEquals(
            $expectedName,
            $user->getName(),
            'Expected to get the same name defined in instantiation.'
        );

        $this->assertEquals(
            $expectedName,
            (string) $user,
            'User object converted to string should result in the user\'s name.'
        );
    }

    public static function provide_name()
    {
        return [
            ['AugustoPascutti'],
            ['NelsonSenna'],
            ['apascutti'],
            ['augustohp'],
            ['a'] // Andrei Zmievski
        ];
    }

    /**
     * @test
     * @dataProvider provide_invalid_name
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage User name is invalid.
     */
    public function name_validation($invalidName)
    {
        new User($invalidName);
    }

    public static function provide_invalid_name()
    {
        return [
            ['/'],
            [''],
            [null]
        ];
    }

    /**
     * @test
     * @depends has_name
     */
    public function user_comparison()
    {
        $augusto = new User('augustohp');
        $nelson = new User('nelsonsar');

        $this->assertNotEquals(
            $augusto,
            $nelson,
            'Two different users should not be equals.'
        );

        $this->assertEquals(
            $augusto,
            new User($augusto->getName()),
            'Two userd with the same name, should be considered the same user.'
        );
    }
}
