<?php

use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocToCommentFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths(['src']);
    $ecsConfig->ruleWithConfiguration(LineLengthFixer::class, [
        ['max_line_length' => 120]
    ]);
    $ecsConfig->sets([SetList::CLEAN_CODE, SetList::PSR_12, SetList::COMMON]);
    $ecsConfig->skip([
        PhpdocToCommentFixer::class => null,
        NotOperatorWithSuccessorSpaceFixer::class => null
    ]);
};