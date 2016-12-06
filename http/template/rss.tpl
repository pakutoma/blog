<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
    <channel>
        <title>ぱくとまのブログ</title>
        <link>http://pakutoma.pw/</link>
        <description>ぱくとまの日記兼メモ。95%のポエムと5%の何か。</description>
        <language>ja</language>
        <lastBuildDate>{$items[0].pubDate}</lastBuildDate>
        <generator>pakutoma.pw RSS system ver 0.1 for Smarty</generator>
        <docs>http://www.rssboard.org/rss-specification</docs>
{section name=itemlist loop=$items}
        <item>
            <title>
                {$items[itemlist].title}
            </title>
            <link>
                {$items[itemlist].link}
            </link>
            <author>
                pakutoma
            </author>
            <category>
                {$items[itemlist].category}
            </category>
            <description>
                {$items[itemlist].description}
            </description>
            <pubDate>
                {$items[itemlist].pubDate}
            </pubDate>
            <guid isPermaLink="true">{$items[itemlist].link}</guid>
        </item>
{/section}
    </channel>
</rss>
