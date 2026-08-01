{extends file='layout/layout.tpl'}

{block name='title'}{$category->getName()} — {$app_name}{/block}

{block name='content'}
    <section class="category-page">
        <div class="container">
            <div class="category-page__header">
                <h1 class="category-page__title">{$category->getName()}</h1>
                {if $category->getDescription()}
                    <p class="category-page__description">{$category->getDescription()}</p>
                {/if}
            </div>

            <div class="toolbar">
                <span class="toolbar__label">Sort by:</span>
                <a href="/category?id={$category->getId()}&sort=created_at&order=desc"
                   class="toolbar__link{if $sort == 'created_at'} toolbar__link--active{/if}">Date</a>
                <a href="/category?id={$category->getId()}&sort=view_count&order=desc"
                   class="toolbar__link{if $sort == 'view_count'} toolbar__link--active{/if}">Views</a>
                <a href="/category?id={$category->getId()}&sort={$sort}&order={if $order == 'ASC'}desc{else}asc{/if}"
                   class="toolbar__order">{if $order == 'ASC'}&uarr; Asc{else}&darr; Desc{/if}</a>
            </div>

            {if $articles|@count > 0}
                <div class="card-grid">
                    {foreach $articles as $article}
                        <article class="card">
                            <a href="/article?id={$article['article']->getId()}" class="card__image" style="background-image: url('{$article['image_url']}')" aria-label="Read: {$article['article']->getName()}"></a>
                            <div class="card__body">
                                <h3 class="card__title"><a href="/article?id={$article['article']->getId()}">{$article['article']->getName()}</a></h3>
                                <span class="card__date">{$article['article']->getViewCount()} views</span>
                                <p class="card__excerpt">{$article['article']->getDescription()}</p>
                                <a href="/article?id={$article['article']->getId()}" class="card__link">Continue Reading</a>
                            </div>
                        </article>
                    {/foreach}
                </div>

                {if $totalPages > 1}
                    <nav class="pagination">
                        {if $page > 1}
                            <a href="/category?id={$category->getId()}&sort={$sort}&order={$order}&page={$page - 1}" class="pagination__link">&laquo; Prev</a>
                        {/if}

                        {for $p=1 to $totalPages}
                            <a href="/category?id={$category->getId()}&sort={$sort}&order={$order}&page={$p}"
                               class="pagination__link{if $p == $page} pagination__link--active{/if}">{$p}</a>
                        {/for}

                        {if $page < $totalPages}
                            <a href="/category?id={$category->getId()}&sort={$sort}&order={$order}&page={$page + 1}" class="pagination__link">Next &raquo;</a>
                        {/if}
                    </nav>
                {/if}
            {else}
                <p class="category-page__empty">No articles in this category yet.</p>
            {/if}
        </div>
    </section>
{/block}
