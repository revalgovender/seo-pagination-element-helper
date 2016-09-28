<?php

namespace Test;

use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit_Framework_TestCase;
use Revalgovender\SeoPaginationElementHelper\SeoPaginationElementHelper;

class SeoPaginationElementHelperTest extends PHPUnit_Framework_TestCase
{
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Setup LengthAwarePaginator object for tests
     *
     * @param string $dataPath
     *
     * @return LengthAwarePaginator
     */
    public function setupData(string $dataPath) : LengthAwarePaginator
    {
        // Given.
        $data = file_get_contents(__DIR__ . $dataPath);
        $data = json_decode($data);

        // Build Paginator.
        return $data = new LengthAwarePaginator(
            $data->data,
            $data->total,
            $data->per_page,
            $data->current_page,
            ['path' => 'http://local.laravel-seo-pagination-helper.co.uk/blog/articles']
        );
    }

    public function testBuildNextElement()
    {
        $lengthAwarePaginator = $this->setupData(self::DS .'data'. self::DS . 'paginatorResultSetPageOne.json');
        $seoPaginatorElementHelper = new SeoPaginationElementHelper($lengthAwarePaginator);

        // Expected values.
        $expectedUrl =
            '<link rel="next" href="http://local.laravel-seo-pagination-helper.co.uk/blog/articles?page=2"/>';

        // Assertions.
        $this->assertEquals(
            $expectedUrl,
            $seoPaginatorElementHelper->buildNextElement()
        );
    }

    public function testBuildPreviousElement()
    {
        $lengthAwarePaginator = $this->setupData(self::DS . 'data' . self::DS . 'paginatorResultSetPageFive.json');
        $seoPaginatorElementHelper = new SeoPaginationElementHelper($lengthAwarePaginator);

        // Expected values.
        $expectedUrl =
            '<link rel="prev" href="http://local.laravel-seo-pagination-helper.co.uk/blog/articles?page=4"/>';

        // Assertions.
        $this->assertEquals(
            $expectedUrl,
            $seoPaginatorElementHelper->buildPreviousElement()
        );
    }

    public function testGenerateHtml()
    {
        // Given.
        $lengthAwarePaginator = $this->setupData(self::DS . 'data'. self::DS . 'paginatorResultSetPageFive.json');
        $seoPaginatorElementHelper = new SeoPaginationElementHelper($lengthAwarePaginator);
        $rel = 'prev';
        $href = "http://local.laravel-seo-pagination-helper.co.uk/blog/articles?page=4";

        // Actions.
        $generatedHtml = $seoPaginatorElementHelper ->generateHtml($rel, $href);

        // Expected values.
        $expectedHtml =
            '<link rel="prev" href="http://local.laravel-seo-pagination-helper.co.uk/blog/articles?page=4"/>';

        // Assertions.
        $this->assertEquals($expectedHtml, $generatedHtml);
    }

    public function testGetElementsForPageFive()
    {
        // Given.
        $lengthAwarePaginator = $this->setupData(self::DS . 'data' . self::DS . 'paginatorResultSetPageFive.json');
        $seoPaginatorElementHelper = new SeoPaginationElementHelper($lengthAwarePaginator);

        // Actions.
        $elements = $seoPaginatorElementHelper->getElements();

        // Expected values.
        $mockElements = [
            '<link rel="prev" href="http://local.laravel-seo-pagination-helper.co.uk/blog/articles?page=4"/>',
            '<link rel="next" href="http://local.laravel-seo-pagination-helper.co.uk/blog/articles?page=6"/>',
        ];

        // Assertions.
        $this->assertCount(2, $elements);
        $this->assertEquals($mockElements, $elements);
    }

    public function testGetElementsForPageOne()
    {
        $lengthAwarePaginator = $this->setupData(self::DS . 'data' . self::DS . 'paginatorResultSetPageOne.json');
        $seoPaginatorElementHelper = new SeoPaginationElementHelper($lengthAwarePaginator);

        // Actions.
        $elements = $seoPaginatorElementHelper->getElements();

        // Expected values.
        $mockElements = [
            '<link rel="next" href="http://local.laravel-seo-pagination-helper.co.uk/blog/articles?page=2"/>',
        ];

        // Assertions.
        $this->assertCount(1, $elements);
        $this->assertEquals($mockElements, $elements);
    }

    public function testGetElementsForPageOneWithTwoEntriesOnly()
    {
        $lengthAwarePaginator = $this->setupData(self::DS . 'data' . self::DS . 'paginatorResultSetPageOneWithTwoEntries.json');
        $seoPaginatorElementHelper = new SeoPaginationElementHelper($lengthAwarePaginator);

        // Actions.
        $elements = $seoPaginatorElementHelper->getElements();

        // Expected values.
        $mockElements = [];

        // Assertions.
        $this->assertCount(0, $elements);
        $this->assertEquals($mockElements, $elements);
    }

    public function testGetElementsForTheLastPage()
    {
        $lengthAwarePaginator = $this->setupData(self::DS . 'data' . self::DS . 'paginatorResultSetPageSeven.json');
        $seoPaginatorElementHelper = new SeoPaginationElementHelper($lengthAwarePaginator);

        // Actions.
        $elements = $seoPaginatorElementHelper->getElements();

        // Expected values.
        $mockElements = [
            '<link rel="prev" href="http://local.laravel-seo-pagination-helper.co.uk/blog/articles?page=6"/>',
        ];

        // Assertions.
        $this->assertCount(1, $elements);
        $this->assertEquals($mockElements, $elements);
    }
}