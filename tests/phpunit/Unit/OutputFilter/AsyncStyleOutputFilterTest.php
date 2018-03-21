<?php # -*- coding: utf-8 -*-

namespace Inpyde\Assets\Tests\Unit\OutputFilter;

use Brain\Monkey;
use Inpsyde\Assets\Asset;
use Inpsyde\Assets\Tests\Unit\AbstractTestCase;
use Inpyde\Assets\OutputFilter\AssetOutputFilter;
use Inpyde\Assets\OutputFilter\AsyncStyleOutputFilter;

class AsyncStyleOutputFilterTest extends AbstractTestCase
{

    public function testBasic()
    {

        static::assertInstanceOf(AssetOutputFilter::class, new AsyncStyleOutputFilter());
    }

    public function testRender()
    {

        $testee = new AsyncStyleOutputFilter();

        $expectedUrl = 'foo.jpg';
        $input = '<link rel="stylesheet" url="'.$expectedUrl.'" />';

        Monkey\Functions\when('esc_url')->justReturn($expectedUrl);

        $stub = \Mockery::mock(Asset::class);
        $stub->expects('url')->once()->andReturn($expectedUrl);

        $output = $testee($input, $stub);

        static::assertContains('<link rel="preload" href="'.$expectedUrl.'" as="style"', $output);
        // is input wrapped into <noscript>-Tag?
        static::assertContains("<noscript>{$input}</noscript>", $output);
        // polyfill
        static::assertContains('<script>', $output);
        static::assertContains('</script>', $output);
    }
}
