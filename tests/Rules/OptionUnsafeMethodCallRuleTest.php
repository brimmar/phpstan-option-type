<?php

declare(strict_types=1);

namespace Brimmar\PHPStan\Tests\Rules;

use Brimmar\PHPStan\Rules\OptionUnsafeMethodCallRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<OptionUnsafeMethodCallRule>
 */
class OptionUnsafeMethodCallRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new OptionUnsafeMethodCallRule('Brimmar\PhpOption\Interfaces\Option');
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../extension.neon'];
    }

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/Data/UnsafeFixtures.php'], [
            [
                'Potentially unsafe use of unwrap() on Option type without proper checks. Consider using isSome() checks, match(), or unwrapOr() for safer handling.',
                13,
            ],
            [
                'Calling unwrap() on None. None has no value.',
                23,
            ],
            [
                'Calling unwrap() on None. None has no value.',
                31,
            ],
            [
                'Calling expect() on None. None has no value.',
                46,
            ],
        ]);
    }
}
