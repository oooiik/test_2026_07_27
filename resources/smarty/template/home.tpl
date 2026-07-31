{$smarty.now}

{foreach $data as $d}
    <br>
    <h3>
        {$d['category']->getId()} => {$d['category']->getName()}
    </h3>
    {foreach $d['lastArticles'] as $article}
        <br>
        <h4>
            {$article['article']->getName()}
        </h4>
        <img src="{$article['image_url']}" alt="">
        {$article['article']->getText()}
    {/foreach}
{/foreach}