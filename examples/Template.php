<?php

namespace doc\GW\Value;

use GW\Value\AssocArray;
use GW\Value\AssocValue;
use GW\Value\StringValue;
use GW\Value\Wrap;
use function file_get_contents;

final class Template
{
    private StringValue $template;
    /** @var AssocValue<string, string> */
    private AssocValue $params;

    public function __construct(StringValue $template)
    {
        $this->template = $template;
        /** @phpstan-var array<string, string> $a :(( */
        $a = [];
        $this->params = new AssocArray($a);
    }

    public static function fromFile(string $path): self
    {
        return new self(Wrap::string(file_get_contents($path) ?: ''));
    }

    public static function fromString(string $template): self
    {
        return new self(Wrap::string($template));
    }

    /** @param AssocValue<string, string> $params */
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
