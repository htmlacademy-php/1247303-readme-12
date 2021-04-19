
<main class="page__main page__main--adding-post tabs__content--active">
      <div class="page__main-section">
        <div class="container">
          <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
          <div class="adding-post__tabs-wrapper tabs">
            <div class="adding-post__tabs filters">
              <ul class="adding-post__tabs-list filters__list tabs__list">
              <?php foreach ($types_content as $key => $type):?>
                <li class="adding-post__tabs-item filters__item">
                  <a class="adding-post__tabs-link filters__button filters__button--<?=$type['class_name']?> <?= ((int) $type['id'] === $get_id) ? 'filters__button--active tabs__item--active button  tabs__item' : '' ?>" href="add.php?categories-id=<?=$type['id'];?>">
                    <svg class="filters__icon" width="22" height="18">
                      <use xlink:href="#icon-filter-<?=$type['class_name']?>"></use>
                    </svg>
                    <span><?=$type['type']?></span>
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
            <div class="adding-post__tab-content">
                <?=$add_post_form?>
            </div>
          </div>
        </div>
      </div>
    </main>