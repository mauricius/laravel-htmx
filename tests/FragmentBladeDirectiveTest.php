<?php

declare(strict_types=1);

namespace Mauricius\LaravelHtmx\Tests;

use RuntimeException;
use Spatie\Snapshots\MatchesSnapshots;

class FragmentBladeDirectiveTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function the_view_still_renders_correctly_if_it_contains_fragments()
    {
        $message = 'htmx';

        $renderedView = view()->make('basic', compact('message'))->render();

        $this->assertMatchesSnapshot($renderedView);
    }

    /** @test */
    public function it_throws_an_exception_if_the_specified_fragment_does_not_exists_in_the_view()
    {
        $fragment = 'missing';
        $view = 'basic';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches("/No fragment called \"$fragment\" exists in \".*\/tests\/views\/$view\.blade\.php\"/m");

        view()->renderFragment($view, $fragment);
    }

    /** @test */
    public function it_throws_an_exception_if_the_specified_fragment_exists_multiple_times_in_the_view()
    {
        $fragment = 'duplicate';
        $view = 'duplicate';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches("/Multiple fragments called \"$fragment\" exists in \".*\/tests\/views\/$view\.blade\.php\"/m");

        view()->renderFragment($view, $fragment);
    }

    /** @test */
    public function the_render_fragment_view_macro_can_render_a_single_fragment_whose_name_is_enclosed_in_double_quotes()
    {
        $message = 'htmx';

        $renderedView = view()->renderFragment('basic', 'double', compact('message'));

        $this->assertMatchesSnapshot($renderedView);
    }

    /** @test */
    public function the_render_fragment_view_macro_can_render_a_single_fragment_whose_name_is_enclosed_in_single_quotes()
    {
        $message = 'htmx';

        $renderedView = view()->renderFragment('basic', 'single', compact('message'));

        $this->assertMatchesSnapshot($renderedView);
    }

    /** @test */
    public function the_render_fragment_view_macro_can_render_a_single_fragment_defined_inline()
    {
        $message = 'htmx';

        $renderedView = view()->renderFragment('inline', 'inline', compact('message'));

        $this->assertMatchesSnapshot($renderedView);
    }

    /** @test */
    public function the_render_fragment_view_macro_can_render_a_single_fragment_even_if_it_is_nested_in_other_fragments()
    {
        $renderedView = view()->renderFragment('nested', 'inner');

        $this->assertMatchesSnapshot($renderedView);
    }

    /** @test */
    public function the_render_fragment_view_macro_can_render_a_single_fragment_even_if_it_is_not_aligned_with_the_closing_fragment()
    {
        $renderedView = view()->renderFragment('misaligned', 'inner');

        $this->assertMatchesSnapshot($renderedView);
    }

    /** @test */
    public function the_render_fragment_view_macro_can_render_a_single_fragment_even_if_it_it_contains_multibyte_characters()
    {
        $message = 'htmx';

        $renderedView = view()->renderFragment('multibyte', 'fünf', compact('message'));

        $this->assertMatchesSnapshot($renderedView);
    }
}
