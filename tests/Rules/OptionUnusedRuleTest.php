<?php

declare(strict_types=1);

namespace Brimmar\PHPStan\Tests\Rules;

use Brimmar\PHPStan\Rules\OptionUnusedRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<OptionUnusedRule>
 */
class OptionUnusedRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new OptionUnusedRule(self::createReflectionProvider());
    }

    public function testRule(): void
    {
        // (void), $_, a bare variable, a property fetch, and self::/static::
        // with NoDiscard must stay quiet. self::make() without it must not.
        $this->analyse([__DIR__ . '/Data/UnusedFixtures.php'], [
            [
                'Unused Brimmar\PhpOption\Interfaces\Option. This value may be None, which should be handled.',
                19,
            ],
            [
                'Unused Brimmar\PhpOption\Some<mixed>. This value must be used.',
                34,
            ],
            [
                'Unused Brimmar\PhpOption\Interfaces\Option. This value may be None, which should be handled.',
                63,
            ],
        ]);
    }
}
