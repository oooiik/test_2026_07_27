{$smarty.now}

{foreach $data as $d}
    <br>
    <h3>
        {$d['category']->getId()} => {$d['category']->getName()}
    </h3>
    {foreach $d['lastArticles'] as $article}
        <br>
        <h4>
            {$article->getName()}
        </h4>
        {$article->getText()}
    {/foreach}
{/foreach}