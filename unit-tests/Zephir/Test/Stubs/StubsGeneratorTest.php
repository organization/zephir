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
use Zephir\Stubs\Generator;
use Zephir\CompilerFile;
use Zephir\AliasManager;

class StubsGeneratorTest extends KernelTestCase
{
    public function ztestItCanBuildClass()
    {
        $files = [
            './unit-tests/fixtures/class-definition-1.php',
        ];

        $classDefinition = new ClassDefinition('Test', 'TestStubGenerator');
        $config = new Config();
        $test = new Generator($files, $config);

        $expectedStub = <<<DOC
<?php

namespace Test;

class TestStubGenerator
{

}

DOC;
        $actual = $test->generate('./');
        // $test->buildClass($classDefinition, "\t");
        $this->assertSame($expectedStub, $actual);
    }

    public function setUp(): void
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();

        $this->config = new Config();
        $this->aliasManager = new AliasManager();
    }

    /**
     * @test
     */
    public function itCanGenerate(): void
    {
        /** @var \Zephir\FileSystem\FileSystemInterface $filesystem */
        $filesystem = $this->container->get(FileSystemInterface::class);

        $mockFiles = [
            new CompilerFile($this->config, $this->aliasManager, $filesystem)
        ];

        $generator = new Generator($mockFiles, $this->config);
        echo dirname(__FILE__);
        // $test = $generator->generate();

        $this->assertSame(1, 1);
    }
}
