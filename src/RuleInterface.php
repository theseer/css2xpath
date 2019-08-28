<?php declare(strict_types = 1);
namespace TheSeer\CSS2XPath;

interface RuleInterface {
    public function apply(string $selector): string;
}
