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
    public function the_render_fragment_view_macro_can_render_an_inline_fragment()
    {
        $renderedView = view()->renderFragment('inline', 'inline');

        $this->assertMatchesSnapshot($renderedView);
    }

    /** @test */
    public function the_render_fragment_view_macro_can_render_a_fragment_even_if_it_contains_nested_fragments()
    {
        $message = 'htmx';

        $renderedView = view()->renderFragment('nested', 'outer', compact('message'));

        $this->assertMatchesSnapshot($renderedView);
    }

    /** @test */
    public function it_throws_an_exception_if_the_specified_fragment_does_not_exists()
    {
        $fragment = 'missing';
        $view = 'basic';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches("/No fragment called \"$fragment\" exists in \".*\/tests\/views\/$view\.blade\.php\"/m");

        view()->renderFragment($view, $fragment);
    }
}
