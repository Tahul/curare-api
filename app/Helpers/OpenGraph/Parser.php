<?php

namespace App\Helpers\OpenGraph {

    use DOMDocument;
    use Exception;

    class Parser {
        /**
         * Parse content into an array.
         * @param $url string
         * @return array
         */
        public static function parse(string $url) {
            $domain = parse_url($url)["host"];
            $ogp = [
                'domain' => $domain,
                'favicon' => 'https://www.google.com/s2/favicons?domain=' . $domain
            ];

            try {
                // Get the page content
                $content = file_get_contents($url);

                // Fudge to handle a situation when an encoding isn't present
                if (strpos($content, 'xml encoding=')===false) $content = '<?xml encoding="utf-8" ?>' . $content;

                $doc = new DOMDocument();
                @$doc->loadHTML($content);

                // Open graph namespaces we're interested in (open graph + extensions)
                $interested_in = ['description', 'keywords', 'og', 'fb', 'twitter'];

                // Title
                $titleTag = $doc->getElementsByTagName('title')->item(0);
                if (!is_null($titleTag)) {
                    $ogp['title'] = $titleTag->nodeValue;
                }

                // Open graph
                $metas = $doc->getElementsByTagName('meta');
                if (!empty($metas)) {
                    foreach ($metas as $meta) {
                        foreach (['name', 'property'] as $name) {
                            $attribute = $meta->getAttribute($name);
                            $meta_bits = explode(':', $attribute);
                            if (in_array($meta_bits[0], $interested_in)) {
                                // If we're adding to an existing element, convert it to an array
                                if (isset($ogp[$attribute]) && (!is_array($ogp[$attribute])))
                                    $ogp[$meta_bits[0]][$attribute] = array($ogp[$attribute], $meta->getAttribute('content'));
                                else if (isset($ogp[$attribute]) && (is_array($ogp[$attribute])))
                                    $ogp[$meta_bits[0]][$attribute][] = $meta->getAttribute('content');
                                else
                                    $ogp[$meta_bits[0]][$attribute] = $meta->getAttribute('content');
                            }
                        }
                    }
                }

                // OEmbed
                $metas = $doc->getElementsByTagName('link');
                if (!empty($metas)) {
                    foreach ($metas as $meta) {
                        if (strtolower($meta->getAttribute('rel')) == 'alternate') {

                            if (in_array(strtolower($meta->getAttribute('type')), ['application/json+oembed'])) {
                                $ogp['oembed']['jsonp'][] = $meta->getAttribute('href');
                            }
                            if (in_array(strtolower($meta->getAttribute('type')), ['text/json+oembed'])) {
                                $ogp['oembed']['json'][] = $meta->getAttribute('href');
                            }
                            if (in_array(strtolower($meta->getAttribute('type')), ['text/xml+oembed'])) {
                                $ogp['oembed']['xml'][] = $meta->getAttribute('href');
                            }
                        }
                    }
                }

                // Basics
                foreach (['title'] as $basic) {
                    if (preg_match("#<$basic>(.*?)</$basic>#siu", $content, $matches))
                        $ogp[$basic] = trim($matches[1], " \n");
                }

                $metas = $doc->getElementsByTagName('meta');

                if (!empty($metas)) {
                    foreach ($metas as $meta) {
                        if (strtolower($meta->getAttribute('name')) == 'description') {
                            $ogp['description'] = $meta->getAttribute('content');
                        }
                        if (strtolower($meta->getAttribute('name')) == 'keywords') {
                            $ogp['keywords'] = $meta->getAttribute('content');
                        }
                    }
                }
            } catch (Exception $e) {
                // Neglect this; failed to parse OpenGraph but still return the minimal viable content.
            }

            return $ogp;
        }
    }
}
