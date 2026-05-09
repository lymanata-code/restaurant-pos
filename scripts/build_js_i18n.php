<?php
/**
 * Bundles all PHP lang/{en,km}/*.php files into JSON files for vue-i18n.
 *
 * Output:
 *   resources/js/admin/i18n/en.json
 *   resources/js/admin/i18n/km.json
 *
 * Existing keys from JSON are preserved; PHP keys overwrite duplicates.
 */
$base = dirname(__DIR__);

foreach (['en', 'km'] as $loc) {
    $bundle = [];
    $dir = "$base/lang/$loc";
    if (is_dir($dir)) {
        foreach (glob("$dir/*.php") as $file) {
            $ns = basename($file, '.php');
            $arr = require $file;
            if (is_array($arr)) {
                $bundle[$ns] = $arr;
            }
        }
    }
    // Append a few common DataTable keys.
    $bundle['datatable'] = $bundle['datatable'] ?? [
        'search' => $loc === 'km' ? 'ស្វែងរក៖' : 'Search:',
        'lengthMenu' => $loc === 'km' ? 'បង្ហាញ _MENU_' : 'Show _MENU_',
        'info' => $loc === 'km' ? 'បង្ហាញ _START_ ដល់ _END_ ក្នុងចំនួន _TOTAL_' : 'Showing _START_ to _END_ of _TOTAL_ entries',
        'infoEmpty' => $loc === 'km' ? 'បង្ហាញ 0 ដល់ 0 ក្នុងចំនួន 0' : 'Showing 0 to 0 of 0 entries',
        'infoFiltered' => $loc === 'km' ? '(ត្រងពី _MAX_ ទាំងអស់)' : '(filtered from _MAX_ total entries)',
        'zeroRecords' => $loc === 'km' ? 'រកមិនឃើញទិន្នន័យទេ' : 'No matching records found',
        'emptyTable' => $loc === 'km' ? 'មិនមានទិន្នន័យទេ' : 'No data available',
        'processing' => $loc === 'km' ? 'កំពុងផ្ទុក…' : 'Loading…',
        'first' => $loc === 'km' ? 'ដំបូង' : 'First',
        'last' => $loc === 'km' ? 'ចុងក្រោយ' : 'Last',
        'previous' => $loc === 'km' ? 'មុន' : 'Previous',
        'next' => $loc === 'km' ? 'បន្ទាប់' : 'Next',
    ];

    $jsonPath = "$base/resources/js/admin/i18n/$loc.json";
    file_put_contents($jsonPath, json_encode($bundle, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "wrote $jsonPath\n";
}
echo "done\n";
