<?php declare(strict_types = 1);
namespace TheSeer\CSS2XPath;

class NotRule implements RuleInterface {

    /** @var Translator */
    private $translator;

    public function __construct(Translator $translator) {
        $this->translator = $translator;
    }

    public function apply(string $selector): string {
        return \preg_replace_callback(
            '/([a-zA-Z0-9\_\-\*]+):not\(([^\)]*)\)/',
            [$this, 'callback'],
            $selector
        );
    }

    private function callback(array $matches): string {
        $subresult = \preg_replace(
            '/^[^\[]+\[([^\]]*)\].*$/',
            '$1',
            $this->translator->translate($matches[2])
        );

        return $matches[1] . '[not(' . $subresult . ')]';
    }
}
