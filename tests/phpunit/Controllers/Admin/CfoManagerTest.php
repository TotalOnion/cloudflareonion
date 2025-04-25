<?php

namespace Tests\Admin;

use PHPUnit\Framework\TestCase;
use GlobalCfo\Controllers\Admin\CfoManager;

final class CfoManagerTest extends TestCase
{
    public static function oldUrlProvider(): array
    {
        return [
            'Do not change same domain'   => [
                'https://www.newdomain.com/en/products/',
                'https://www.newdomain.com/en/products/',
            ],
            'Change domain'    => [
                'https://www.olddomain.com/en/cocktails/',
                'https://www.newdomain.com/en/cocktails/'
            ],
            'Ignored empty paths'       => [
                '',
                ''
            ],
        ];
    }

    /**
     * @dataProvider oldUrlProvider
     */
    public function testDomainReplacedURL(string $input, string $expected): void
    {
        $mock = $this->getMockBuilder(CfoManager::class)
                ->setConstructorArgs([GLOBAL_CFO_NAME, GLOBAL_CFO_VERSION])
                ->onlyMethods(['getDomainReplace'])
                ->getMock();
        $mock->method('getDomainReplace')
            ->willReturn('www.newdomain.com');
        $result = $mock->getDomainReplacedURL($input);
        $this->assertEquals($expected, $result);
    }
}