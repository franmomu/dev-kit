<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Action\Exception;

use App\Action\Exception\CannotDetermineNextRelease;
use App\Domain\Value\Project;
use App\Tests\Domain\Value\ProjectTest;
use Ergebnis\Test\Util\Helper;
use Packagist\Api\Result\Package;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

final class CannotDeterminNextReleaseTest extends TestCase
{
    use Helper;

    /**
     * @test
     */
    public function forProject()
    {
        $package = new Package();
        $package->fromArray([
            'name' => $packageName = 'sonata-project/admin-bundle',
            'repository' => 'https://github.com/sonata-project/SonataAdminBundle',
        ]);

        $config = Yaml::parse(ProjectTest::DEFAULT_CONFIG);

        $project = Project::fromValues(
            ProjectTest::DEFAULT_CONFIG_NAME,
            $config[ProjectTest::DEFAULT_CONFIG_NAME],
            $package
        );

        $cannotDetermineNextRelease = CannotDetermineNextRelease::forProject($project);

        self::assertInstanceOf(
            \RuntimeException::class,
            $cannotDetermineNextRelease
        );
        self::assertSame(
            sprintf(
                'Cannot determine next release for Project "%s".',
                $project->name()
            ),
            $cannotDetermineNextRelease->getMessage()
        );
    }
}
