<?php

namespace doc\GW\Value;

use GW\Value\Wrap;
use GW\Value\ArrayValue;
use GW\Value\Stream;

final class ConsumerAsStream
{
    /** @var ArrayValue */
    private $handlers;

    public function __construct(ArrayValue $handlers)
    {
        $this->handlers = $handlers;
    }

    public function openStream(Stream $rabbitQueue): void
    {
        $messages = $rabbitQueue
            ->map(
                function (string $serializedMessage): Message {
                    return unserialize($serializedMessage, Message::class);
                }
            );

        $this->handlers->each(
            function (Handler $handler) use ($messages): void {
                $handler->openStream($messages);
            }
        );
    }
}

interface Message
{
}

class FooMessage implements Message
{
    public function name(): string
    {
        return 'boo';
    }
}

interface Handler
{
    public function openStream(Stream $messages);
}

class FooHandler implements Handler
{
    public function openStream(Stream $messages)
    {
        $messages
            ->filter(
                function (Message $message): bool {
                    return $message instanceof FooMessage;
                }
            )
            ->listen([$this, 'handle']);
    }

    public function handle(FooMessage $message): void
    {
        echo $message->name() . "\n";
    }
}

$stream = new BufferedStream(new Stream(), 10);
$stream->emit(new FooMessage());

(new ConsumerAsStream(Wrap::arrayFromValues(new FooHandler())))->openStream($stream);
