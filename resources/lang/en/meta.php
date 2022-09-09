<?php

declare(strict_types=1);

return [
    'name' => 'SEO',
    'tab_name' => '<i class="fas fa-chart-line me-1"></i>SEO',
    'description' => 'The term SEO is an abbreviation of the phrase <code>Search Engine Optimization</code> and implies a set of measures aimed at optimizing the output of the site by search engines for target queries.',
    'columns' => [
        'title' => 'Page title',
        'description' => 'Description',
        'keywords' => 'Keywords',
        'heading_h1' => 'Heading H1',
        'robots' => 'Indexing (robots)',
        'is_active' => 'Activate the page',
        'show_nested' => 'Show as a folder',
        'publish_at' => 'Date and time of publication',
        'template' => 'Page template',
        'views' => 'Views',
        'redirect' => 'Redirect',
    ],
    'descriptions' => [
        'title' => '&lt;title &gt; &lt;/title &gt; - displayed on the browser tab, as well as in the search results, with a link to the site.',
        'description' => '&lt;meta name= "description" &gt; - a short description of the page, use keywords here.',
        'keywords' => '&lt;meta name= "keywords" &gt; - general keywords about the page content.',
        'heading_h1' => '&lt;h1&gt; - page heading. By default, the page name is used.',
        'robots' => 'Setting up page indexing by search engines',
        'is_active' => 'Only the included pages are displayed on the site and are contained in the site map.',
        'publish_at' => 'Until this time, the page returns a 404 error. Even if the page is enabled.',
        'show_nested' => 'Suitable for creating a blog or news.',
        'redirect' => '301 Moved Permanently',
    ],
    'placeholder' => [
        'title' => 'Google truncates titles longer than 50-60 characters',
        'description' => 'The length of the meta description should not be shorter than three or four words and no longer than 140-160 characters',
        'keywords' => '',
        'robots' => 'Setting up page indexing',
        'redirect' => 'Redirect the user to the URL',
    ],
    'double_url_error' => 'The link "%s" already exists.',
    'robots' => [
        'info' => [
            'noindex' => 'Do not index the page text. The page will not be included in the search results.',
            'nofollow' => 'Don\'t click on links on the page. The robot will not follow the links when crawling the site, but it can learn about them from other sources. For example, on other pages or sites.'
        ]
    ],
];
