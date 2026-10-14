<?php

declare(strict_types=1);

namespace Brimmar\PHPStan\Tests\Rules;

use Brimmar\PHPStan\Rules\OptionShapeRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<OptionShapeRule>
 */
class OptionShapeRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new OptionShapeRule(
            'Brimmar\PhpOption\Interfaces\Option',
            'Brimmar\PhpResult\Interfaces\Result',
        );
    }

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/Data/ShapeFixtures.php'], [
            [
                'Option::flatten() is for Option<Option<T>>. The contained type is int.',
                15,
            ],
            [
                'Option::flatten() is for Option<Option<T>>. The contained type is int.',
                27,
            ],
            [
                'Option::unzip() is for an array. The contained type is int.',
                49,
            ],
            [
                'Option::unzip() is for an array. The contained type is int.',
                61,
            ],
            [
                'Option::transpose() is for Option<Result<T, E>>. The contained type is int.',
                67,
            ],
        ]);
    }
}
