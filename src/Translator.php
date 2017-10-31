<?php declare(strict_types = 1);
namespace TheSeer\CSS2XPath;

/**
 * Class Translator
 *
 * The regular expressions used in this class are heavily inspired by and mostly adopted from
 * the css2xpath.js code by Andrea Giammarchi (http://code.google.com/p/css2xpath/).
 * The JavaScript version (css2xpath.js) is licensed under the MIT License
 *
 */
class Translator {

    /** @var array */
    private $rules;

    public function translate(string $selector): string {
        foreach($this->getRules() as $rule) {
            /** @var RuleInterface $rule */
            $selector = $rule->apply($selector);
        }

        return $selector === '/' ? '/' : '//' . $selector;
    }

    private function getRules(): array {
        if ($this->rules != null) {
            return $this->rules;
        }

        $this->rules = [

            // prefix|name
            new RegexRule('/([a-zA-Z0-9\_\-\*]+)\|([a-zA-Z0-9\_\-\*]+)/', '$1:$2'),

            // add @ for attribs
            new RegexRule("/\[([^G\]~\$\*\^\|\!]+)(=[^\]]+)?\]/", '[@$1$2]'),

            // multiple queries
            new RegexRule("/\s*,\s*/", '|'),

            // , + ~ >
            new RegexRule("/\s*([\+~>])\s*/", '$1'),

            //* ~ + >
            new RegexRule("/([a-zA-Z0-9\_\-\*])~([a-zA-Z0-9\_\-\*])/", '$1/following-sibling::$2'),
            new RegexRule("/([a-zA-Z0-9\_\-\*])\+([a-zA-Z0-9\_\-\*])/", '$1/following-sibling::*[1]/self::$2'),
            new RegexRule("/([a-zA-Z0-9\_\-\*])>([a-zA-Z0-9\_\-\*])/", '$1/$2'),

            // all unescaped stuff escaped
            new RegexRule("/\[([^=]+)=([^'|][^\]]*)\]/", '[$1="$2"]'),

            // all descendant or self to //
            new RegexRule("/(^|[^a-zA-Z0-9\_\-\*])([#\.])([a-zA-Z0-9\_\-]+)/", '$1*$2$3'),
            new RegexRule("/([\>\+\|\~\,\s])([a-zA-Z\*]+)/", '$1//$2'),
            new RegexRule("/\s+\/\//", '//'),

            // :first-child
            new RegexRule("/([a-zA-Z0-9\_\-\*]+):first-child/", '*[1]/self::$1'),

            // :last-child
            new RegexRule("/([a-zA-Z0-9\_\-\*]+)?:last-child/", '$1[not(following-sibling::*)]'),

            // :only-child
            new RegexRule("/([a-zA-Z0-9\_\-\*]+):only-child/", '*[last()=1]/self::$1'),

            // :empty
            new RegexRule("/([a-zA-Z0-9\_\-\*]+)?:empty/", '$1[not(*) and not(normalize-space())]'),

            // :not
            new NotRule($this),

            // :nth-child
            new NthChildRule(),

            // :contains(selectors)
            new RegexRule('/:contains\(([^\)]*)\)/', '[contains(string(.),"$1")]'),

            // |= attrib
            new RegexRule("/\[([a-zA-Z0-9\_\-]+)\|=([^\]]+)\]/", '[@$1=$2 or starts-with(@$1,concat($2,"-"))]'),

            // *= attrib
            new RegexRule("/\[([a-zA-Z0-9\_\-]+)\*=([^\]]+)\]/", '[contains(@$1,$2)]'),

            // ~= attrib
            new RegexRule("/\[([a-zA-Z0-9\_\-]+)~=([^\]]+)\]/", '[contains(concat(" ",normalize-space(@$1)," "),concat(" ",$2," "))]'),

            // ^= attrib
            new RegexRule("/\[([a-zA-Z0-9\_\-]+)\^=([^\]]+)\]/", '[starts-with(@$1,$2)]'),

            // $= attrib
            new DollarEqualRule(),

            // != attrib
            new RegexRule("/\[([a-zA-Z0-9\_\-]+)\!=[\"']+?([^\"\]]+)[\"']+?\]/", '[not(@$1) or @$1!="$2"]'),

            // ids
            new RegexRule("/#([a-zA-Z0-9\_\-]+)/", '[@id="$1"]'),

            // classes
            new RegexRule("/\.([a-zA-Z0-9_-]+)(?![^[]*])/", '[contains(concat(" ",normalize-space(@class)," ")," $1 ")]'),

            // normalize multiple filters
            new RegexRule("/\]\[([^\]]+)/", ' and ($1)'),

            // tag:pseudo selectors
            new RegexRule('/(:enabled)/', '[not(@disabled)]'),
            new RegexRule("/(:checked)/", '[@checked="checked"]'),
            new RegexRule("/:(disabled)/", '[@$1]'),
            new RegexRule("/:root/", '/'),

            // use * when tag was omitted
            new RegexRule("/^\[/", "*["),
            new RegexRule("/\|\[/", "|*[")
        ];

        return $this->rules;
    }
}
