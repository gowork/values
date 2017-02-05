<?php

namespace doc\GW\Value;

use GW\Value\Arrays;
use GW\Value\AssocValue;
use GW\Value\Strings;
use GW\Value\StringValue;

final class Template
{
    /** @var StringValue */
    private $template;

    /** @var AssocValue <string, string> */
    private $params;

    public function __construct(StringValue $template, AssocValue $params = null)
    {
        $this->template = $template;
        $this->params =  ($params ?? Arrays::assoc())->map('strval');
    }

    public static function fromFile(string $path): self
    {
        return new self(Strings::create(file_get_contents($path)));
    }

    public static function fromString(string $template): self
    {
        return new self(Strings::create($template));
    }

    public function withParams(AssocValue $params): self
    {
        $clone = clone $this;
        $clone->params = $this->params->merge($params);

        return $clone;
    }

    public function withParam(string $key, string $value): self
    {
        $clone = clone $this;
        $clone->params = $this->params->with($key, $value);

        return $clone;
    }

    public function render(): StringValue
    {
        return $this->template->replacePatternCallback('/(\n?)\{\{(.+?)\}\}(\n?)/', function (array $match): string {
            $key = trim($match[2]);

            // Remove tag with surrounding newlines
            // when no para for tag is defined
            $trimLeft = $match[1];
            $trimRight = $match[3];

            return $this->params->has($key) ? $trimLeft . $this->params->get($key) . $trimRight : '';
        });
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
