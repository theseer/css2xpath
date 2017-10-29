<?php declare(strict_types = 1);
namespace TheSeer\CSS2XPath;

class DollarEqualRule implements RuleInterface {

    public function apply(string $selector): string {
        return preg_replace_callback(
            '/\[([a-zA-Z0-9\_\-]+)\$=([^\]]+)\]/',
            [$this, 'callback'],
            $selector
        );
    }

    /**
     * Build query from matches.
     *
     * @param array $matches
     *
     * @return string
     */
    private function callback(array $matches) {
        return '[substring(@' . $matches[1] . ',string-length(@' . $matches[1] . ')-' . (strlen($matches[2]) - 3) . ')=' . $matches[1] . ']';
    }
}
