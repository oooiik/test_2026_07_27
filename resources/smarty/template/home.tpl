{extends file='layout/layout.tpl'}

{block name='title'}Blogy — Blog Categories{/block}

{block name='content'}
    {foreach $data as $d}
        <section class="category">
            <div class="container">
                <div class="category__header">
                    <h2 class="category__title">{$d['category']->getName()}</h2>
                    <a href="/category?id={$d['category']->getId()}" class="view-all">View All</a>
                </div>

                <div class="card-grid">
                    {foreach $d['lastArticles'] as $article}
                        <article class="card">
                            <a href="/article?id={$article['article']->getId()}" class="card__image" style="background-image: url('{$article['image_url']}')" aria-label="Read: {$article['article']->getName()}"></a>
                            <div class="card__body">
                                <h3 class="card__title"><a href="/article?id={$article['article']->getId()}">{$article['article']->getName()}</a></h3>
                                <p class="card__excerpt">{$article['article']->getDescription()}</p>
                                <a href="/article?id={$article['article']->getId()}" class="card__link">Continue Reading</a>
                            </div>
                        </article>
                    {/foreach}
                </div>
            </div>
        </section>
    {/foreach}
{/block}
