<?php declare(strict_types = 1);
namespace TheSeer\CSS2XPath;

class RegexRule implements RuleInterface {

    /** @var string */
    private $regex;

    /** @var string */
    private $replacement;

    public function __construct(string $regex, string $replacement) {
        $this->regex = $regex;
        $this->replacement = $replacement;
    }

    public function apply(string $selector): string {
        return preg_replace($this->regex, $this->replacement, $selector);
    }

}
