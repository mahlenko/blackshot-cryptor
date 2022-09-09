<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml/">
@foreach($urls as $url)
<url>
    <loc>{{ $url->loc }}</loc>
    <lastmod>{{ $url->lastmod }}</lastmod>
    <changefreq>{{ $url->changefreq }}</changefreq>
    <priority>{{ number_format($url->priority, 1) }}</priority>
@if($url->alternative)
@foreach($url->alternative as $alternative)
    <xhtml:link
        rel="{{ $alternative->rel }}"
        hreflang="{{ $alternative->hreflang == 'ua' ? 'uk' : $alternative->hreflang}}"
        href="{{ $alternative->href }}"/>
@endforeach
@endif
</url>
@endforeach
</urlset>
