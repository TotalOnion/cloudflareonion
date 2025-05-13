<?php

namespace Tests\Utils;

use PHPUnit\Framework\TestCase;

final class FunctionsTest extends TestCase
{
    public static function encodedPathsProvider(): array
    {
        return [
            'Do not change already encoded'   => [
                '/%e6%9d%a1%e6%ac%be%e5%92%8c%e6%9d%a1%e4%bb%b6/',
                '/%e6%9d%a1%e6%ac%be%e5%92%8c%e6%9d%a1%e4%bb%b6/',
            ],
            'Encode non latin paths'    => [
                '/zh-cn/条款和条件/',
                '/zh-cn/%e6%9d%a1%e6%ac%be%e5%92%8c%e6%9d%a1%e4%bb%b6/'
            ],
            'Do not encode slashes'     => [
                '/something/产品信息/',
                '/something/%e4%ba%a7%e5%93%81%e4%bf%a1%e6%81%af/'
            ],
            'Do not encode slashes [2]'     => [
                '/a/b/c/d/e/%f',
                '/a/b/c/d/e/%f'
            ],
            'Ignored empty paths'       => [
                '',
                ''
            ],
        ];
    }

    /**
     * @dataProvider encodedPathsProvider
     */
    public function testEncodeNonLatinCharacters(string $input, string $expected): void
    {
        $encoded = encodeNonLatinCharacters($input);
        $this->assertEquals($encoded, $expected);
    }


    //=======================
    //=======================
    //=======================


    public static function invalidPathsProvider(): array
    {
        return [
            'Paths must start with /'       => [
                'a/b/c',
                false
            ],
            'Empty paths are ignored'       => [
                '',
                false
            ],
            'Urls are not paths'            => [
                'http://chivas.com/a/b/c',
                false
            ],
            'Empty slashes can make valid paths'  => [
                '////m/a/b/c',
                true
            ],
            'Paths with invalid characters are not accepted'       => [
                '/[claude]/m/a/b/c',
                false
            ],
            'Paths with invalid characters are not accepted'       => [
                '/{claude}/<>/a/b/c',
                false
            ],
        ];
    }

    /**
     * @dataProvider invalidPathsProvider
     */
    public function testInvalidUrlPaths(string $input, bool $expected): void
    {
        $url = isValidUrlPath($input);
        $this->assertEquals($url, $expected);
    }


    //=======================
    //=======================
    //=======================

    public static function isValidPathProvider(): array
    {
        return [
            'Accept valid path'   => [
                '/ko-kr/range/%EB%B0%9C%EB%A0%8C%ED%83%80%EC%9D%B87%EB%85%84-%EB%B2%84%EB%B2%88%ED%94%BC%EB%8B%88%EC%89%AC/',
                true
            ],
            'Must start with /'    => [
                '/hello/world/',
                true
            ],
            'Must start with / [2]'    => [
                'hello/world/',
                false
            ],
            'Accept non latin paths'    => [
                '/zh-cn/条款和条件/',
                true
            ],
            'Reject non latin paths [2]'    => [
                '/zh-cn/إسرائيل/',
                true
            ],
            'Reject invalid url path'    => [
                '/zh-cn/<hello></hello>',
                false
            ],
            'Reject path with non ascii characters'    => [
                '/en/AC/Ææ½⅓¼⅕⅙⅐⅛⅑/',
                true
            ],
            'Reject path with non ascii characters [2]'    => [
                '/en/Düsseldorf/Köln-Москва-!@#$/',
                true
            ],
            'Reject full urls'    => [
                'http://www.test.com/my-path/hello',
                false
            ],
            'Reject full urls [2]'    => [
                'https://test.com/my-path/hello',
                false
            ]
        ];
    }

    /**
     * @dataProvider isValidPathProvider
     */
    public function testPathIsValid(string $input, bool $expected): void
    {
        $check = isValidUrlPath($input);
        $this->assertEquals($check, $expected);
    }
}
