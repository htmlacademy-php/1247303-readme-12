<div class="post__main">
    <div class="post-link__wrapper">
        <a class="post-link__external" href="http://<?= $post[0]['site_path'] ?>" title="Перейти по ссылке">
            <div class="post-link__icon-wrapper">
                <img src="https://www.google.com/s2/favicons?domain=<?= $post[0]['site_path'] ?>" alt="Иконка">
            </div>
            <div class="post-link__info">
                <h3><?= $post[0]['title'] ?></h3>
                <p><?= $post[0]['site_path']) ?></p>
                <span><?= $post[0]['site_path'] ?></span>
            </div>
            <svg class="post-link__arrow" width="11" height="16">
                <use xlink:href="#icon-arrow-right-ad"></use>
            </svg>
        </a>
    </div>
</div>