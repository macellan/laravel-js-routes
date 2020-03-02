<?php

namespace Macellan\LaravelJsRoutes;

use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router;
use Macellan\LaravelJsRoutes\Generators\RoutesJavascriptGenerator;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class RoutesJavascriptGeneratorTest extends TestCase
{

    protected static $templatesDir;

    public function __construct()
    {
        parent::__construct();
        static::$templatesDir = __DIR__.'/../../src/Way/Generators/Generators/templates';
    }

    protected function tearDown(): void
    {
        m::close();
    }

    protected function getRouter()
    {
        $router = new Router(new Dispatcher);
        $router->get('user/{id}', ['as' => 'user.show', 'uses' => function ($id) {
            return $id;
        }]);
        $router->post('user', ['as' => 'user.store', 'before' => 'js-routable', 'uses' => function ($id) {
            return $id;
        }]);
        $router->get('/user/{id}/edit', ['as' => 'user.edit', 'before' => 'js-routable', 'uses' => function ($id) {
            return $id;
        }]);
        $router->get('/unnamed_route', ['uses' => function ($id) {
            return $id;
        }]);
        return $router;
    }

    /** @test **/
    public function it_can_generate_javascript()
    {
        $file = m::mock('Illuminate\Filesystem\Filesystem')->makePartial();

        $file->shouldReceive('isWritable')
             ->once()
             ->andReturn(true);

        $file->shouldReceive('put')
             ->once()
             ->with('/foo/bar/routes.js', file_get_contents(__DIR__.'/stubs/javascript.txt'));

        $generator = new RoutesJavascriptGenerator($file, $this->getRouter());
        $generator->make('/foo/bar', 'routes.js', ['object' => 'Router']);
    }

    /** @test **/
    public function it_can_generate_javascript_with_custom_object()
    {
        $file = m::mock('Illuminate\Filesystem\Filesystem')->makePartial();

        $file->shouldReceive('isWritable')
             ->once()
             ->andReturn(true);

        $file->shouldReceive('put')
             ->once()
             ->with('/foo/bar/routes.js', file_get_contents(__DIR__.'/stubs/custom-object.txt'));

        $generator = new RoutesJavascriptGenerator($file, $this->getRouter());
        $generator->make('/foo/bar', 'routes.js', ['object' => 'MyRouter']);
    }

    /** @test **/
    public function it_can_generate_javascript_with_custom_filter()
    {
        $file = m::mock('Illuminate\Filesystem\Filesystem')->makePartial();

        $file->shouldReceive('isWritable')
             ->once()
             ->andReturn(true);

        $file->shouldReceive('put')
             ->once()
             ->with('/foo/bar/routes.js', file_get_contents(__DIR__.'/stubs/custom-filter.txt'));

        $generator = new RoutesJavascriptGenerator($file, $this->getRouter());
        $generator->make('/foo/bar', 'routes.js', ['filter' => 'js-routable', 'object' => 'Router']);
    }

    /** @test **/
    public function it_can_generate_javascript_with_custom_prefix()
    {
        $file = m::mock('Illuminate\Filesystem\Filesystem')->makePartial();

        $file->shouldReceive('isWritable')
            ->once()
            ->andReturn(true);

        $file->shouldReceive('put')
            ->once()
            ->with('/foo/bar/routes.js', file_get_contents(__DIR__.'/stubs/custom-prefix.txt'));

        $generator = new RoutesJavascriptGenerator($file, $this->getRouter());
        $generator->make('/foo/bar', 'routes.js', ['object' => 'Router', 'prefix' => 'prefix/']);
    }

    /** @test **/
    public function if_fails_on_non_writable_path()
    {
        $file = m::mock('Illuminate\Filesystem\Filesystem')->makePartial();

        $file->shouldReceive('isWritable')
             ->once()
             ->andReturn(false);

        $generator = new RoutesJavascriptGenerator($file, $this->getRouter());
        $output = $generator->make('/foo/bar', 'routes.js', ['filter' => 'js-routable', 'object' => 'Router']);

        $this->assertFalse($output);
    }
}
