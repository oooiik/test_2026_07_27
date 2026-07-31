{extends file='layout/layout.tpl'}

{block name='title'}Page not found — {$app_name}{/block}

{block name='content'}
    <section class="not-found">
        <div class="container">
            <div class="not-found__code">404</div>
            <p class="not-found__message">Page not found</p>
            <a href="/" class="not-found__link">Back to home</a>
        </div>
    </section>
{/block}
