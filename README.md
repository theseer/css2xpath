# CSS2XPath

This is yet another library to translate various CSS selectors to their XPath equivalent.


## Example

```php

$translator = new TheSeer\CSS2XPath\Translator();

// Will output '//*[contains(concat(" ",normalize-space(@attribute)," ")," note ")]']
echo $translator->translate('.note');

// Will output '//*[@id="me"]']
echo $translator->translate('#me');
```

## Installation

You can add this library as a local, per-project dependency to your project using Composer:

```
composer require theseer/css2xpath
```

## Selectors supported

The following selectors are covered:

- #id
- tag#id
- tag #id
- tag
- tag tag
- tag > tag
- tag + tag
- tag ~ tag
- tag, tag
- .classname
- tag.classname
- tag .classname
- tag.classname, tag.classname
- tag.classname tag.classname
- tag.classname > tag.classname
- tag#id + tag > tag
- tag[id]:contains(Selectors)
- tag[attribute][attribute]
- tag[attribute]
- tag[attribute=example]
- tag[attribute^=exa]
- tag[class$=mple]
- tag[attribute*=e]
- tag[attribute|=dialog]
- tag[attribute!=made_up]
- tag[attribute!="made_up"]
- tag[attribute~=example]
- tag:not(.classname)
- tag:contains(selectors)
- tag:nth-child(n)
- tag:nth-child(even)
- tag:nth-child(odd)
- tag:nth-child(3n+8)
- tag:nth-child(2n+1)
- tag:nth-child(3)
- tag:nth-child(4n)
- tag:only-child
- tag:last-child
- tag:first-child
- foo|bar
- tag[attribute^=exa][attribute$=mple]
- tag:enabled
- tag:checked
- tag:disabled
- :empty
- :root

