<?php
include('../settings.php');
$db->query("select * from structure WHERE vis=1 AND page=0 ORDER BY ordr;");
$pages = $db->get_records();
$layout = '
    <url>
    	<loc>http://'.$_SERVER["HTTP_HOST"].'/</loc>
    	<lastmod>'.date('c').'</lastmod>
    	<priority>1.00</priority>
    	<changefreq>weekly</changefreq>
    </url>
    ';
foreach($pages as $page) {
    if ($page['parentId'] == 0) {
        $priority = '0.90';
    } else {
        $priority = '0.80';
    }
    $layout .= '
    <url>
    	<loc>http://'.$_SERVER["HTTP_HOST"].'/'.$page['uri'].'/</loc>
    	<lastmod>'.date('c').'</lastmod>
    	<priority>'.$priority.'</priority>
    	<changefreq>weekly</changefreq>
    </url>
    ';
}
$out = '
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
'.$layout.'
</urlset>
';
$file = fopen($_SERVER["DOCUMENT_ROOT"].'/sitemap.xml', 'w+');
fputs ($file, $out);
fclose ($file);
?>