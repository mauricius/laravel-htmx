<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Tests\View;

use Mauricius\LaravelHtmx\View\BladeFragment;
use Mauricius\LaravelHtmx\View\BladeFragmentParser;
use Mauricius\LaravelHtmx\View\CloseFragmentElement;
use Mauricius\LaravelHtmx\View\OpenFragmentElement;
use PHPUnit\Framework\TestCase;

class BladeFragmentParserTest extends TestCase
{
    private BladeFragmentParser $parser;

    public function setUp(): void
    {
        parent::setUp();

        $this->parser = new BladeFragmentParser(BladeFragment::OPEN, BladeFragment::CLOSE);
    }

    /** @test */
    public function it_should_return_an_empty_array_of_nodes_when_parsing_an_empty_string()
    {
        $content = "";

        $nodes = $this->parser->parse($content);

        $this->assertEmpty($nodes);
    }

    /** @test */
    public function it_should_return_an_empty_array_of_nodes_when_parsing_blade_code_that_does_not_contain_any_fragments()
    {
        $content = <<<BLADE
            @if (true)
                <p>Foo</p>
            @endif
        BLADE;

        $nodes = $this->parser->parse($content);

        $this->assertEmpty($nodes);
    }

    /** @test */
    public function it_should_return_the_right_nodes_when_parsing_fragments_that_are_defined_on_a_single_line()
    {
        $content = <<<BLADE
            <p>Start</p>
            @fragment("foo") hello world @endfragment
            <p>End</p>
        BLADE;

        $nodes = $this->parser->parse($content);

        $this->assertCount(2, $nodes);

        $this->assertInstanceOf(OpenFragmentElement::class, $nodes[0]);
        $this->assertEquals(21, $nodes[0]->startOffset);
        $this->assertEquals(37, $nodes[0]->endOffset);
        $this->assertEquals('foo', $nodes[0]->name);

        $this->assertInstanceOf(CloseFragmentElement::class, $nodes[1]);
        $this->assertEquals(50, $nodes[1]->startOffset);
    }

    /** @test */
    public function it_should_return_the_right_nodes_when_parsing_fragments_that_are_defined_on_multiple_lines()
    {
        $content = <<<BLADE
            <p>Start</p>
            @fragment("foo")
                <h1>hello world</h1>
            @endfragment
            <p>End</p>
        BLADE;

        $nodes = $this->parser->parse($content);

        $this->assertCount(2, $nodes);

        $this->assertInstanceOf(OpenFragmentElement::class, $nodes[0]);
        $this->assertEquals(21, $nodes[0]->startOffset);
        $this->assertEquals(37, $nodes[0]->endOffset);
        $this->assertEquals('foo', $nodes[0]->name);

        $this->assertInstanceOf(CloseFragmentElement::class, $nodes[1]);
        $this->assertEquals(71, $nodes[1]->startOffset);
    }

    /** @test */
    public function it_should_return_the_right_nodes_when_parsing_fragments_that_are_defined_non_uniformly()
    {
        $content = <<<BLADE
            <p>Start</p>@fragment("foo")
            <h1>hello world</h1>
            <h2>subtitle</h2>@endfragment
            <p>End</p>
        BLADE;

        $nodes = $this->parser->parse($content);

        $this->assertCount(2, $nodes);

        $this->assertInstanceOf(OpenFragmentElement::class, $nodes[0]);
        $this->assertEquals(16, $nodes[0]->startOffset);
        $this->assertEquals(32, $nodes[0]->endOffset);
        $this->assertEquals('foo', $nodes[0]->name);

        $this->assertInstanceOf(CloseFragmentElement::class, $nodes[1]);
        $this->assertEquals(79, $nodes[1]->startOffset);
    }

    /** @test */
    public function it_should_return_nodes_even_when_parsing_unclosed_fragments()
    {
        $content = <<<BLADE
            <p>Start</p>
            @endfragment
            <h2>subtitle</h2>
            @fragment("foo")
            <h1>hello world</h1>
            <p>End</p>
        BLADE;

        $nodes = $this->parser->parse($content);

        $this->assertCount(2, $nodes);

        $this->assertInstanceOf(CloseFragmentElement::class, $nodes[0]);
        $this->assertEquals(21, $nodes[0]->startOffset);

        $this->assertInstanceOf(OpenFragmentElement::class, $nodes[1]);
        $this->assertEquals(60, $nodes[1]->startOffset);
        $this->assertEquals(76, $nodes[1]->endOffset);
        $this->assertEquals('foo', $nodes[1]->name);
    }

    /** @test */
    public function it_should_still_return_nodes_when_parsing_open_and_close_fragments_on_multiple_lines()
    {
        $content = <<<BLADE
            <p>Start</p>
            @fragment("foo") @fragment("bar") <h1>hello world</h1> @endfragment
            @endfragment
            <p>End</p>
        BLADE;

        $nodes = $this->parser->parse($content);

        $this->assertCount(4, $nodes);

        $this->assertInstanceOf(OpenFragmentElement::class, $nodes[0]);
        $this->assertEquals(21, $nodes[0]->startOffset);
        $this->assertEquals(37, $nodes[0]->endOffset);
        $this->assertEquals('foo', $nodes[0]->name);

        $this->assertInstanceOf(OpenFragmentElement::class, $nodes[1]);
        $this->assertEquals(38, $nodes[1]->startOffset);
        $this->assertEquals(54, $nodes[1]->endOffset);
        $this->assertEquals('bar', $nodes[1]->name);

        $this->assertInstanceOf(CloseFragmentElement::class, $nodes[2]);
        $this->assertEquals(76, $nodes[2]->startOffset);

        $this->assertInstanceOf(CloseFragmentElement::class, $nodes[3]);
        $this->assertEquals(93, $nodes[3]->startOffset);
    }
}
