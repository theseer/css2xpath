<?php declare(strict_types = 1);
namespace TheSeer\CSS2XPath;

class NthChildRule implements RuleInterface {

    public function apply(string $selector): string {
        return preg_replace_callback(
            '/([a-zA-Z0-9_\-*]+):nth-child\(([^)]*)\)/',
            [$this, 'callback'],
            $selector
        );
    }

    private function callback(array $matches): string {
        switch ($matches[2]) {
            case 'n': {  // :nth-child(n)
                return $matches[1];
            }
            case 'even': { // :nth-child(even)
                return sprintf('%s[(count(preceding-sibling::*) + 1) mod 2=0]', $matches[1]);
            }
            case 'odd': { // :nth-child(odd)
                return $matches[1] . '[(count(preceding-sibling::*) + 1) mod 2=1]';
            }
            case preg_match('/^\d*$/', $matches[2]) === 1: { // :nth-child(1)
                return sprintf('*[%d]/self::%s', $matches[2], $matches[1]);
            }
            default: { // :nth-child(1n+2)
                $b = preg_replace('/^([\d]*)n.*?([\d]*)$/', '$1+$2', $matches[2]);
                $b = explode('+', $b);

                return sprintf(
                    '%s[(count(preceding-sibling::*)+1)>=%d and ((count(preceding-sibling::*)+1)-%d) mod %d=0]',
                    $matches[1],
                    $b[1],
                    $b[1],
                    $b[0]
                );
            }
        }
    }

}

