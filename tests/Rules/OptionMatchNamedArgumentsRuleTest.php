<?php

declare(strict_types=1);

namespace Brimmar\PHPStan\Tests\Rules;

use Brimmar\PHPStan\Rules\OptionMatchNamedArgumentsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<OptionMatchNamedArgumentsRule>
 */
class OptionMatchNamedArgumentsRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new OptionMatchNamedArgumentsRule('Brimmar\PhpOption\Interfaces\Option');
    }

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/Data/MatchFixtures.php'], [
            [
                'Option::match() must use named arguments "Some" and "None".',
                11,
            ],
            [
                'Option::match() must use named arguments "Some" and "None".',
                22,
            ],
            [
                'Option::match() must have exactly two arguments.',
                27,
            ],
        ]);
    }
}
