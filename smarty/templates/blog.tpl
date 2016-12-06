<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="blog.css">
    <link rel="icon" href="http://pakutoma.pw/favicon.ico" type="image/vnd.microsoft.icon" />
    {if isset($title)}
        <title>{$title} - ぱくとまのブログ</title>
    {else}
        <title>ぱくとまのブログ</title>
    {/if}
    <meta charset="UTF-8">
    {literal}
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-71955623-1', 'auto');
        ga('send', 'pageview');
    </script>
    {/literal}
</head>
<body>
    <div id="blogtitle"><a href="/">ぱくとまのブログ</a></div>
    <div id="enclose">
        <div id="texts">
            {if isset($next)||isset($prev)}
                <div id=paging>
                    {if isset($prev)}
                        <a href="/?page={$prev -> pagenum}">&lt; {$prev -> title}</a>
                    {/if}
                    {if isset($next)&&isset($prev)}
                         |
                    {/if}
                    {if isset($next)}
                        <a href="/?page={$next -> pagenum}">{$next -> title} &gt;</a>
                    {/if}
                </div>
            {/if}
            <div id="main" class="main whiteBox">
                {foreach from=$main item=item}
                    <h2 id="title" class="title">{$item.title}</h2>
                    <p id="date" class="date">{$item.date}</p>
                    <p id="text" class="text">{$item.text}</p>
                    {if isset($item.pagenum)}
                        <a href="/?page={$item.pagenum}" class="readMore">続きを読む</a>
                    {/if}
                {/foreach}
            </div>
        </div>
        <div id="other">
            <div class="linktitle">最新記事</div>
            <div id="latest" class="whiteBox links">
                <ul class="pageLink">
                {section name=latestlink loop=$latest}
                    <li><a href="{$latest[latestlink].url}">{$latest[latestlink].title}</a></li>
                {/section}
                </ul>
            </div>
            <div class="linktitle">カテゴリ</div>
            <div id="category" class="whiteBox links">
                <ul class="pageLink">
                {section name=categorylink loop=$category}
                    <li><a href="{$category[categorylink].url}">{$category[categorylink].title}</a></li>
                {/section}
                </ul>
            </div>
            <div class="linktitle">月別アーカイブ</div>
            <div id="archive" class="whiteBox links">
                {foreach from=$archive key=year item=month}
                <details>
                    <summary><span>{$year}年</span></summary>
                    <ul>
                    {foreach from=$month item=item}
                        <li><a href="{$item.url}">{$item.title}</a></li>
                    {/foreach}
                    </ul>
                </details>
                {/foreach}
            </div>
            <div class="linktitle">相互リンク</div>
            <div id="link" class="whiteBox links">
                <ul>
                    <li><a href="http://ayumunpa.blog.fc2.com">あすぺあゆむのあすぺなにちじょう</a></li>
                    <li><a href="http://haya4shi.blogspot.jp/">早氏の暇つぶし</a></li>
                    <li><a href="http://faifor.blogspot.jp/">ふぁいふぉーブログ</a></li>
                    <li><a href="http://homomaid.com/">ほもめいどどっとこむ</a></li>
                    <li><a href="http://jprekz.xyz/">jprekz.xyz</a></li>
                    <li><a href="http://ちきんまん.com/">チキンマンのサイト</a></li>
                    <li><a href="http://chitoku.jp/">ちとくのホームページ</a></li>
                    <li><a href="http://blog.msz3nhen.net/">てっぴーのブログ</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="footer">
        <a href="http://pakutoma.pw/rss.php/">
            <img src="rss.png" alt="RSS購読">
            <!-- RSSアイコンはhttp://designsozai.com/様のものを使用させて頂きましたありがとうございます -->
        </a>
        <br /><span>&#169; pakutoma 2014-2016</span>
    </div>
</body>
</html>
