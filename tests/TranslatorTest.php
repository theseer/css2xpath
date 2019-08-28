<?php declare(strict_types = 1);
namespace TheSeer\CSS2XPath;

class TranslatorTest extends \PHPUnit\Framework\TestCase {

    /**
     * @dataProvider provider
     */
    public function testTranslatingCssSelectorReturnsCorrectXPath(string $selector, string $xpath): void {
        $translator = new Translator();
        $this->assertEquals($xpath, $translator->translate($selector));
    }

    public function provider(): array {
        return [
            'div'                          => ['div', '//div'],
            'body div'                     => ['body div', '//body//div'],
            'div p'                        => ['div p', '//div//p'],
            'div > p'                      => ['div > p', '//div/p'],
            'div + p'                      => ['div + p', '//div/following-sibling::*[1]/self::p'],
            'div ~ p'                      => ['div ~ p', '//div/following-sibling::p'],
            'div p a'                      => ['div p a', '//div//p//a'],
            'div, p, a'                    => ['div, p, a', '//div|//p|//a'],
            '.note'                        => ['.note', '//*[contains(concat(" ",normalize-space(@class)," ")," note ")]'],
            'div.example'                  => ['div.example', '//div[contains(concat(" ",normalize-space(@class)," ")," example ")]'],
            'ul .tocline2'                 => ['ul .tocline2', '//ul//*[contains(concat(" ",normalize-space(@class)," ")," tocline2 ")]'],
            'div.example, div.note'        => ['div.example, div.note', '//div[contains(concat(" ",normalize-space(@class)," ")," example ")]|//div[contains(concat(" ",normalize-space(@class)," ")," note ")]'],
            '#title'                       => ['#title', '//*[@id="title"]'],
            'h1#title'                     => ['h1#title', '//h1[@id="title"]'],
            'div #title'                   => ['div #title', '//div//*[@id="title"]'],
            'ul.toc li.tocline2'           => ['ul.toc li.tocline2', '//ul[contains(concat(" ",normalize-space(@class)," ")," toc ")]//li[contains(concat(" ",normalize-space(@class)," ")," tocline2 ")]'],
            'ul.toc > li.tocline2'         => ['ul.toc > li.tocline2', '//ul[contains(concat(" ",normalize-space(@class)," ")," toc ")]/li[contains(concat(" ",normalize-space(@class)," ")," tocline2 ")]'],
            'h1#title + div > p'           => ['h1#title + div > p', '//h1[@id="title"]/following-sibling::*[1]/self::div/p'],
            'h1[id]:contains(Selectors)'   => ['h1[id]:contains(Selectors)', '//h1[@id and (contains(string(.),"Selectors"))]'],
            'a[href][lang][class]'         => ['a[href][lang][class]', '//a[@href and (@lang) and (@class)]'],
            'div[class]'                   => ['div[class]', '//div[@class]'],
            'div[class=example]'           => ['div[class=example]', '//div[@class="example"]'],
            'div[class^=exa]'              => ['div[class^=exa]', '//div[starts-with(@class,"exa")]'],
            'div[class$=mple]'             => ['div[class$=mple]', '//div[substring(@class,string-length(@class)-3)=class]'],
            'div[class*=e]'                => ['div[class*=e]', '//div[contains(@class,"e")]'],
            'div[class|=dialog]'           => ['div[class|=dialog]', '//div[@class="dialog" or starts-with(@class,concat("dialog","-"))]'],
            'div[class!=made_up]'          => ['div[class!=made_up]', '//div[not(@class) or @class!="made_up"]'],
            'div[property!="made_up"]'     => ['div[property!="made_up"]', '//div[not(@property) or @property!="made_up"]'],

            'div[class~=example]'          => ['div[class~=example]', '//div[contains(concat(" ",normalize-space(@class)," "),concat(" ","example"," "))]'],
            'div:not(.example)'            => ['div:not(.example)', '//div[not(contains(concat(" ",normalize-space(@class)," ")," example "))]'],
            'p:contains(selectors)'        => ['p:contains(selectors)', '//p[contains(string(.),"selectors")]'],
            'p:nth-child(n)'               => ['p:nth-child(n)', '//p'],
            'p:nth-child(even)'            => ['p:nth-child(even)', '//p[(count(preceding-sibling::*) + 1) mod 2=0]'],
            'p:nth-child(odd)'             => ['p:nth-child(odd)', '//p[(count(preceding-sibling::*) + 1) mod 2=1]'],
            'p:nth-child(3n+8)'            => ['p:nth-child(3n+8)', '//p[(count(preceding-sibling::*)+1)>=8 and ((count(preceding-sibling::*)+1)-8) mod 3=0]'],
            'p:nth-child(2n+1)'            => ['p:nth-child(2n+1)', '//p[(count(preceding-sibling::*)+1)>=1 and ((count(preceding-sibling::*)+1)-1) mod 2=0]'],
            'p:nth-child(3)'               => ['p:nth-child(3)', '//*[3]/self::p'],
            'p:nth-child(4n)'              => ['p:nth-child(4n)', '//p[(count(preceding-sibling::*)+1)>=0 and ((count(preceding-sibling::*)+1)-0) mod 4=0]'],
            'p:only-child'                 => ['p:only-child', '//*[last()=1]/self::p'],
            'p:last-child'                 => ['p:last-child', '//p[not(following-sibling::*)]'],
            'p:first-child'                => ['p:first-child', '//*[1]/self::p'],
            'foo|bar'                      => ['foo|bar', '//foo:bar'],
            'div[class^=exa][class$=mple]' => ['div[class^=exa][class$=mple]', '//div[starts-with(@class,"exa") and (substring(@class,string-length(@class)-3)=class)]'],

            'input:enabled'                => ['input:enabled', '//input[not(@disabled)]'],
            'input:checked'                => ['input:checked', '//input[@checked="checked"]'],
            'input:disabled'               => ['input:disabled', '//input[@disabled]'],

            ':empty'                       => [':empty', '//*[not(*) and not(normalize-space())]'],
            ':root'                        => [':root', '/']

        ];
    }
}
