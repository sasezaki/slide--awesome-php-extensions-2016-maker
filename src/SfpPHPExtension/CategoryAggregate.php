<?php
namespace SfpPHPExtension;

class CategoryAggregate
{
    private $categories = [
        'Authentication' => [],
        'Benchmarking' => [],
        'Caching' => [],
        'Configuration' => [],
        'Console' => [],
        'Database' => [],
        'Date and Time' => [],
        'Encryption' => [],
        'Event' => [],
        'File Formats' => [],
        'File System' => [],
        'GUI' => [],
        'HTML' => [],
        'HTTP' => [],
        'Images' => [],
        'Internationalization' => [],
        'Languages' => [],
        'Logging' => [],
        'Mail' => [],
        'Math' => [],
        'Multimedia' => [],
        'Networking' => [],
        'Numbers' => [],
        'Payment' => [],
        'PHP' => [],
        'Processing' => [],
        'Search Engine' => [],
        'Security' => [],
        'Streams' => [],
        'Structures' => [],
        'System' => [],
        'Text' => [],
        'Tools and Utilities' => [],
        'Virtualization' => [],
        'Web Services' => [],
        'XML' => [],
        'Unkown' => [],
    ];

    public function divide($extlist)
    {
        $return = [];
        foreach ($this->categories as $category => $l) {
            foreach ($extlist as $name => $ext) {
                if ($category === $ext['category']) {
                    $return[$category][$name] = $ext;
                }
            }
        }

        return $return;
    }
}