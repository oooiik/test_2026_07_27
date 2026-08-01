{extends file='layout/layout.tpl'}

{block name='title'}{$article->getName()} — {$app_name}{/block}

{block name='content'}
    <section class="article-page">
        <div class="container">
            <div class="article-page__breadcrumb">
                <a href="/category?id={$category->getId()}">{$category->getName()}</a>
            </div>

            <h1 class="article-page__title">{$article->getName()}</h1>

            <div class="article-page__meta">{$article->getViewCount()} views</div>

            <div class="article-page__image" style="background-image: url('{$image_url}')"></div>

            {if $article->getDescription()}
                <p class="article-page__excerpt">{$article->getDescription()}</p>
            {/if}

            <div class="article-page__text">{$article->getText()|escape|nl2br}</div>
        </div>
    </section>

    {if $similarArticles|@count > 0}
        <section class="category">
            <div class="container">
                <div class="category__header">
                    <h2 class="category__title">Similar Articles</h2>
                </div>

                <div class="card-grid">
                    {foreach $similarArticles as $similar}
                        <article class="card">
                            <a href="/article?id={$similar['article']->getId()}" class="card__image" style="background-image: url('{$similar['image_url']}')" aria-label="Read: {$similar['article']->getName()}"></a>
                            <div class="card__body">
                                <h3 class="card__title"><a href="/article?id={$similar['article']->getId()}">{$similar['article']->getName()}</a></h3>
                                <p class="card__excerpt">{$similar['article']->getDescription()}</p>
                                <a href="/article?id={$similar['article']->getId()}" class="card__link">Continue Reading</a>
                            </div>
                        </article>
                    {/foreach}
                </div>
            </div>
        </section>
    {/if}
{/block}
