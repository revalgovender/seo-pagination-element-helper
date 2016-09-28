<?php
/**
 * @author    Reval Govender
 * @copyright 2016 Reval Govender
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://revgov.co.uk
 */

namespace Revalgovender\SeoPaginationElementHelper;

use Illuminate\Pagination\LengthAwarePaginator;

/*
|--------------------------------------------------------------------------
| Seo Pagination Helper
|--------------------------------------------------------------------------
|
| This class generates HTML link elements rel=”next” and rel=”prev” to indicate the relationship between component URLs
| in a paginated series. This helps Google to:
| 1. Consolidate indexing properties, such as links, from the component pages/URLs to the series as a whole (i.e., links
|    should not remain dispersed between page-1.html, page-2.html, etc., but be grouped with the sequence)
| 2. Send users to the most relevant page/URL—typically the first page of the series.
*/

class SeoPaginationElementHelper
{
    /**
     * @var LengthAwarePaginator
     */
    protected $paginator;

    /**
     * SeoPaginationElementHelper constructor.
     *
     * @param LengthAwarePaginator $paginator
     */
    public function __construct(LengthAwarePaginator $paginator)
    {
        $this->setPaginator($paginator);
    }

    /*
    |--------------------------------------------------------------------------
    | Logic
    |--------------------------------------------------------------------------
    */

    /**
     * Build the rel="next" element.
     *
     * @return string
     */
    public function buildNextElement() : string
    {
        $href = $this->getPaginator()->nextPageUrl();

        return $this->generateHtml('next', $href);
    }

    /**
     * Build the rel="prev" element.
     *
     * @return string
     */
    public function buildPreviousElement() :string
    {
        $href = $this->getPaginator()->previousPageUrl();

        return $this->generateHtml('prev', $href);
    }

    /**
     * Generate the HTML needed for the element.
     *
     * @param string $rel
     * @param string $href
     *
     * @return string
     */
    public function generateHtml(string $rel, string $href) : string
    {
        return '<link rel="' . $rel . '" href="' . $href . '"/>';
    }

    /**
     * Determine which elements to send to the front end.
     *
     * @return array
     */
    public function getElements() : array
    {
        if ($this->getPaginator()->onFirstPage() === true && !$this->getPaginator()->hasMorePages()) {
            return [];
        }

        if ($this->getPaginator()->onFirstPage()) {
            return [$this->buildNextElement()];
        }

        if ($this->getPaginator()->currentPage() == $this->getPaginator()->lastPage()) {
            return [$this->buildPreviousElement()];
        }

        return [$this->buildPreviousElement(), $this->buildNextElement()];
    }

    /*
    |--------------------------------------------------------------------------
    | Setters and Getters
    |--------------------------------------------------------------------------
    */

    /**
     * @return LengthAwarePaginator
     */
    public function getPaginator(): LengthAwarePaginator
    {
        return $this->paginator;
    }

    /**
     * @param LengthAwarePaginator $paginator
     */
    public function setPaginator(LengthAwarePaginator $paginator)
    {
        $this->paginator = $paginator;
    }
}
