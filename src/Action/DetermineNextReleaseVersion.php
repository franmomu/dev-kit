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

namespace App\Action;

use App\Domain\Value\Stability;
use App\Github\Domain\Value\PullRequest;
use App\Github\Domain\Value\Release\Tag;

final class DetermineNextReleaseVersion
{
    /**
     * @param PullRequest[] $pullRequests
     */
    public static function forTagAndPullRequests(Tag $current, array $pullRequests): Tag
    {
        if ([] === $pullRequests) {
            return $current;
        }

        $stabilities = array_map(static function (PullRequest $pr): string {
            return $pr->stability()->toString();
        }, $pullRequests);

        $parts = explode('.', $current->toString());

        if (\in_array(Stability::minor()->toString(), $stabilities, true)) {
            return Tag::fromString(implode('.', [$parts[0], (int) $parts[1] + 1, 0]));
        }

        if (\in_array(Stability::patch()->toString(), $stabilities, true)) {
            return Tag::fromString(implode('.', [$parts[0], $parts[1], (int) $parts[2] + 1]));
        }

        return $current;
    }
}
