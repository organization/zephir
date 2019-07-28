<?php

/*
 * This file is part of the Zephir.
 *
 * (c) Zephir Team <team@zephir-lang.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zephir\Test\Stubs;

use League\Flysystem\Filesystem as Flysystem;
use Zephir\FileSystem\FileSystemInterface;
use Zephir\Test\KernelTestCase;
use Zephir\ClassDefinition;
use Zephir\Config;
use Zephir\Parser\Parser;
use Zephir\Stubs\Generator;
use Zephir\CompilerFile;
use Zephir\AliasManager;

class StubsGeneratorTest extends KernelTestCase
{
    public function expectedStub()
    {
        return <<<DOC
<?php

namespace Fixtures\Ide_Stubs;

use Fixtures\Ide_Stubs\Interfaces\DiInterfaceExample;

class BaseClassExample
{

}

DOC;
    }

    /** @test */
    public function itCanBuildClass()
    {
        self::bootKernel(['config_files' => [__DIR__.'/../../../config.yml']]);

        $container = self::$kernel->getContainer();
        $config = self::$kernel->getContainer()->get(Config::class);

        /** @var \Zephir\FileSystem\FileSystemInterface $compilerFs */
        $compilerFs = $container->get(FileSystemInterface::class);

        $file = './unit-tests/Zephir/fixtures/ide_stubs/BaseClassExample.zep';
        $indent = "\t";

        $parser = new Parser();
        $parsed = $parser->parse($file);

        $classDefinition = new ClassDefinition($parsed[0]['name'], $parsed[3]['name']);

        /** @var \Zephir\CompilerFile $compilerFile */
        $compilerFile = new CompilerFile($config, new AliasManager(), $compilerFs);

        $test = new Generator($parsed, $config);

        $generatedStub = $this->invokeMethod($test, 'buildClass', [$classDefinition, $indent]);

        $this->assertSame($this->expectedStub(), $generatedStub);

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function invokeMethod(&$object, $methodName, $parameters = [])
    {
        $reflection = new \ReflectionClass(\get_class($object));

        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
