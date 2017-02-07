<?php

namespace doc\GW\Value;

use GW\Value\StringValue;
use GW\Value\Wrap;

final class ReadmeWriter
{
    public function describeClasses(array $classes): StringValue
    {
        $markdown = Template::fromFile(__DIR__ . '/template/README.md');

        $classTemplates = Wrap::array($classes)
            ->map(function(string $classRef): Template {
                $class = new \ReflectionClass($classRef);
                $classTemplate = $this->classTemplate($class);

                $methodTemplates = Wrap::array($class->getMethods())
                    ->map(function(\ReflectionMethod $method): Template {
                        return $this->methodTemplate($method);
                    });

                return $classTemplate->withParam('methods', $methodTemplates->implode(''));
            });

        return $markdown->withParam('classes', $classTemplates->implode(''))->render();
    }

    private function classTemplate(\ReflectionClass $class): Template
    {
        $className = $this->className($class->name);
        $descriptionFile = __DIR__ . "/template/{$className}.md";

        $template = Template::fromFile(__DIR__ . '/template/Class.md')->withParam('name', $className);

        if (file_exists($descriptionFile)) {
            $template = $template->withParam('description', Template::fromFile($descriptionFile)->render());
        }

        return $template;
    }

    private function methodTemplate(\ReflectionMethod $method): Template
    {
        $className = $this->className($method->class);
        $methodName = $method->name;
        $declaration = $this->methodDeclaration($method)->trim()->toString();
        $doc = $this->methodDoc($method)->toString();

        $definition = Wrap::string('*(definition not available)*');

        if ($declaration || $doc) {
            $definition = Template::fromFile(__DIR__ . '/template/ClassMethodDefinition.md')
                ->withParams(Wrap::assocArray(compact('doc', 'declaration')))
                ->render();
        }

        $template = Template::fromFile(__DIR__ . '/template/ClassMethod.md')
            ->withParams(Wrap::assocArray(compact('className', 'methodName', 'definition')));

        $descriptionFile = __DIR__ . "/template/{$className}-{$methodName}.md";

        if (file_exists($descriptionFile)) {
            $template = $template->withParam('description', Template::fromFile($descriptionFile)->render()->toString());
        }

        $example = $this->methodExample($className, $methodName);
        if (!$example->isEmpty()) {
            $template = $template->withParam('example', $example);
        }

        return $template;
    }

    private function className(string $class): string
    {
        return Wrap::string($class)->explode('\\')->last();
    }

    private function methodDeclaration(\ReflectionMethod $method): StringValue
    {
        if (empty($method->getFileName())) {
            return Wrap::string('');
        }

        return $this->getFileLines($method->getFileName(), $method->getStartLine(), $method->getEndLine());
    }

    private function getFileLines(string $file, int $start, int $end): StringValue
    {
        return Wrap::stringsArray(file($file, FILE_IGNORE_NEW_LINES))
            ->slice($start - 1, $end - $start + 1)
            ->implode(PHP_EOL);
    }

    private function methodDoc(\ReflectionMethod $method): StringValue
    {
        return Wrap::string($method->getDocComment())
            ->explode(PHP_EOL)
            ->trim()
            ->map(function(StringValue $line): StringValue {
                return $line->substring(0, 1)->toString() === '*' ? $line->prefix(Wrap::string(' ')) : $line;
            })
            ->implode(PHP_EOL);
    }

    private function methodExample(string $class, string $method): StringValue
    {
        $exampleFile = __DIR__ . "/example/{$class}-{$method}.php";

        if (!file_exists($exampleFile)) {
            return Wrap::string('');
        }

        ob_start();
        include $exampleFile;
        $output = ob_get_contents();
        ob_end_clean();

        return Template::fromFile(__DIR__ . '/template/ClassMethodExample.md')
            ->withParam('source', trim(file_get_contents($exampleFile)))
            ->withParam('output', trim($output))
            ->render();
    }
}
