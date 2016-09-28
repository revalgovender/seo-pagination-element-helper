https://travis-ci.org/revalgovender/seo-pagination-element-helper.svg?branch=master

#Seo Pagination Element Helper For Laravel 5+

This class generates HTML link elements rel=”next” and rel=”prev” to indicate the relationship between component URLs
in a paginated series. These elements are recommended to be put in by Google for SEO benefits. Please read the following for more information: https://webmasters.googleblog.com/2011/09/pagination-with-relnext-and-relprev.html

## Usage
This class requires an instance of LengthAwarePaginator. The class will then handle the rest.

```php
<?php
    // Get SEO Pagination Elements.
    $seoPaginationElementHelper = new SeoPaginationElementHelper($paginatedArticles);
    $seoPaginationElements = $seoPaginationElementHelper->getElements();
?>
```
The class returns the required elements as an array. You just need to loop through the array to display them in your blade.

    @if (isset($seoPaginationElements))
        @foreach ($seoPaginationElements as $seoPaginationElement)
            {!! $seoPaginationElement !!}
        @endforeach
    @endif
