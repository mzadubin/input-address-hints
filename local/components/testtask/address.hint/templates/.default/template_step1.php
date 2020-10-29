<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**@param array of \Corners5\Project\Catalog\CCategory $arResult["obCategoryesList"] */
?>
<div class="container">
<ul class="fractions jsBtnSelectStepNumber">
  <li class="fractions__item"><span>1</span></li>
  <li class="fractions__item">2</li>

</ul>
 </div>

<div class="background background--gradient">
    <div class="geometry geometry--parent-22">
          <section class="header2">
            <div class="container">
              <h2>Общая информация </h2>
            </div>
          </section>
    </div>
  </div>
  <div class="container">
        <section class="meat">
          <form class="meat__form validator jsFormBaseFields">
            <h4 class="meat__heading">Категория</h4>
            <div class="meat__hint">
              <button class="meat__revial" type="button">?
                <section class="meat__prompt"> 
                  <?= tplvar('testvar1');?>
                </section>
              </button>
              <div class="meat__checkboxes">
                <?
                    $index = 0;
                    foreach($arResult["obCategoryesList"] as $obCategory) {
                        $index++;
                        ?>
                            <input class="meat__input"
                                   value="<?=$obCategory->getId()?>" type="checkbox" id="option-category-list-<?=$index?>" name="arCategoryIds[]">
                            <label class="meat__checkbox" for="option-category-list-<?=$index?>">
                                <?=$obCategory->getName();?>
                            </label>
                        <?
                    }
                ?>
              </div>
            </div>
            <h4 class="meat__heading">Описание</h4>
            <?
            if ($arResult["sObjectType"] == "tender") {
                ?>
                    <div class="meat__date-piker">
                      <div class="meat__date">
                        <input class="meat__date-input" type="date" id="date" name="date" placeholder="Дата окончания">
                      </div>
                      <p class="meat__date-message">После объект автоматически перейдёт в завершенный.</p>
                    </div>
                <?
            } else{
                ?>
                    <div class="meat__date-piker">
                      <div class="meat__date">
                        <input class="meat__date-input" type="date" id="date" name="date" value="<?=date("Y-m-d")?>" placeholder="Дата начала активности">
                      </div>
                      <p class="meat__date-message">Дата начала активности.</p>
                    </div>
                <?
            }
            ?>
            <div class="meat__counter">
              <textarea class="form__textarea meat__field" required type="text" minlength="3" maxlength="360" pattern="^[a-zA-ZА-Яа-яЁё0-9-!$%^&amp;amp;*()_+|~=`{}[:;&amp;lt;&amp;gt;?,.@#№'&amp;quot;]+( [a-zA-ZА-Яа-яЁё0-9-!$%^&amp;amp;*()_+|~=`{}[:;&amp;lt;&amp;gt;?,.@#№'&amp;quot;]+)*$" name="name" placeholder="Наименование"></textarea>
              <p class="meat__count-number"><span>0</span> / 360</p>
            </div>
            <div class="meat__counter">
              <textarea class="form__textarea meat__field" required type="text" minlength="3" maxlength="360" pattern="^[a-zA-ZА-Яа-яЁё0-9-!$%^&amp;amp;*()_+|~=`{}[:;&amp;lt;&amp;gt;?,.@#№'&amp;quot;]+( [a-zA-ZА-Яа-яЁё0-9-!$%^&amp;amp;*()_+|~=`{}[:;&amp;lt;&amp;gt;?,.@#№'&amp;quot;]+)*$" name="description" placeholder="Краткое описание"></textarea>
              <p class="meat__count-number"><span>0</span> / 360</p>
            </div>
            <div class="meat__counter">
              <textarea class="form__textarea meat__field" required type="text" minlength="3" maxlength="360" pattern="^[a-zA-ZА-Яа-яЁё0-9-!$%^&amp;amp;*()_+|~=`{}[:;&amp;lt;&amp;gt;?,.@#№'&amp;quot;]+( [a-zA-ZА-Яа-яЁё0-9-!$%^&amp;amp;*()_+|~=`{}[:;&amp;lt;&amp;gt;?,.@#№'&amp;quot;]+)*$" name="you" placeholder="Расскажите о себе и о объекте"></textarea>
              <p class="meat__count-number"><span>0</span> / 360</p>
            </div>
            <h4 class="meat__heading">Документы</h4>
            <div class="meat__hint">
              <button class="meat__revial" type="button">?
                <section class="meat__prompt"> 
                  <p class="meat__prompt-text">документы...</p>
                  <p class="meat__prompt-heading">Требования:</p>
                  <ul class="meat__prompt-list">
                    <li class="meat__prompt-item">jpeg, png, gif;</li>
                    <li class="meat__prompt-item">Не более 5 mb;</li>
                    <li class="meat__prompt-item">Минимальный размер 750x450 px.</li>
                  </ul>
                </section>
              </button>
              <div class="meat__docs jsContainerObjectDocuments">
                
                <?
                    foreach($arResult["arDocuments"] as $arDocument) {
                        ?>
                            <p class="meat__loaded jsContainerUploadedFileInfo">
                                <?=$arDocument["name"]?>
                                <button class="meat__delete jsBtnDeleteUploadedFileDocuments" data-delete_url="<?=$arDocument["deleteUrl"]?>" type="button">
                                  <svg>
                                    <use href="#delete"></use>
                                  </svg>
                                </button>
                                <span class="meat__type">
                                    <?=$arDocument["arPathInfo"]["extension"]?>
                                </span>
                                <span class="meat__size"><?=formatBytes($arDocument["size"])?></span>
                            </p>
                            
                        <?
                    }
                
           
                ?>
             
                <input class="visually-hidden jsInputObjectDocuments" type="file" id="doc1" name="object_documents[]" multiple>
                <label class="meat__load jsContainerDropzoneObjectDocuments" for="doc1">Прикрепить файл<span class="jsContainerProgressbarObjectDocuments"></span></label>
          
              </div>
            </div>
            <div class="meat__buttons">
            <?/*
                <a class="meat__back jsBtnGoToPrevRegistrationStep" type="button"><span>←</span> Назад</a>
            */?>
              <button class="meat__submit" type="submit">Далее<span>→</span></button>
            </div>
          </form>
        </section>
  </div>

<?/*
      <div class="background background--gradient">
        <div class="geometry geometry--parent-22">
              <section class="header2">
                <div class="container">
                  <h2>Информация об участнике</h2>
                </div>
              </section>
        </div>
      </div>
      <div class="container">
            <section class="meat">
              <p class="meat__skip">Пропустите этот шаг, если не хотите чтоб ваш профиль отображался на сайте.
                <a class="meat__skip-link jsBtnSkipParticipantCart" href="#"><span class="visually-hidden">Пропустите этот шаг, если не хотите чтоб ваш профиль отображался на сайте.</span>
                    <svg>
                      <use href="#sliderArrow"></use>
                    </svg>
                </a>
              </p>
              <form class="meat__form validator jsFormUserRegistrationParticipantCartBaseFields">
                <h4 class="meat__heading">Описание</h4>
                <div class="meat__checkboxes">
                    <?
                        $index = 0;
                        foreach($arResult["obCategoryesList"] as $obCategory) {
                            $index++;
                            ?>
                                <input class="meat__input"
                                       value="<?=$obCategory->getId()?>" type="checkbox" id="option-category-list-<?=$index?>" name="arCategoryIds[]">
                                <label class="meat__checkbox" for="option-category-list-<?=$index?>">
                                    <?=$obCategory->getName();?>
                                </label>
                            <?
                        }
                    ?>
                </div>
                <div class="meat__counter">
                  <textarea class="form__textarea meat__field"
                            required type="text" minlength="3" maxlength="360"
                            pattern="^[a-zA-ZА-Яа-яЁё0-9-!$%^&amp;amp;*()_+|~=`{}[:;&amp;lt;&amp;gt;?,.@#№'&amp;quot;]+( [a-zA-ZА-Яа-яЁё0-9-!$%^&amp;amp;*()_+|~=`{}[:;&amp;lt;&amp;gt;?,.@#№'&amp;quot;]+)*$"
                            name="description" placeholder="Краткое описание участника"></textarea>
                  <p class="meat__count-number"><span>0</span> / 360</p>
                </div>
                <div class="meat__counter">
                  <textarea class="form__textarea meat__field" required type="text" minlength="3" maxlength="360" pattern="^[a-zA-ZА-Яа-яЁё0-9-!$%^&amp;amp;*()_+|~=`{}[:;&amp;lt;&amp;gt;?,.@#№'&amp;quot;]+( [a-zA-ZА-Яа-яЁё0-9-!$%^&amp;amp;*()_+|~=`{}[:;&amp;lt;&amp;gt;?,.@#№'&amp;quot;]+)*$"
                            name="about" placeholder="Расскажите о себе"></textarea>
                  <p class="meat__count-number"><span>0</span> / 360</p>
                </div>
                <h4 class="meat__heading">Документы</h4>
                <div class="meat__hint">
                  <button class="meat__revial" type="button">?
                    <section class="meat__prompt"> 
                      <p class="meat__prompt-text">Эта фотография будет отображаться на странице профиля участника.</p>
                      <p class="meat__prompt-heading">Требования:</p>
                      <ul class="meat__prompt-list">
                        <li class="meat__prompt-item">jpeg, png, gif;</li>
                        <li class="meat__prompt-item">Не более 5 mb;</li>
                        <li class="meat__prompt-item">Минимальный размер 750x450 px.</li>
                      </ul>
                    </section>
                  </button>
                  
                    <div class="meat__docs jsContainerParticipantDocuments">
                        <?
                        foreach($arResult["arDocuments"] as $arDocument) {
                            ?>
                                <p class="meat__loaded jsContainerUploadedFileInfo">
                                    <?=$arDocument["name"]?>
                                    <button class="meat__delete jsBtnDeleteUploadedFileDocuments" data-delete_url="<?=$arDocument["deleteUrl"]?>" type="button">
                                      <svg>
                                        <use href="#delete"></use>
                                      </svg>
                                    </button>
                                    <span class="meat__type">
                                        <?=$arDocument["arPathInfo"]["extension"]?>
                                    </span>
                                    <span class="meat__size"><?=formatBytes($arDocument["size"])?></span>
                                </p>
                                
                            <?
                        }
                        
                   
                        ?>
                     
                        <input class="visually-hidden jsInputParticipantDocuments" type="file" id="doc1" name="participant_documents[]" multiple>
                        <label class="meat__load jsContainerDropzoneParticipantDocuments" for="doc1">Прикрепить файл<span class="jsContainerProgressbarParticipantDocuments"></span></label>
                    </div>
                </div>
                <h4 class="meat__heading">Обложка профиля</h4>
                <div class="meat__hint">
                  <button class="meat__revial" type="button">?
                    <section class="meat__prompt"> 
                      <p class="meat__prompt-text">Эта фотография будет отображаться на странице профиля участника.</p>
                      <p class="meat__prompt-heading">Требования:</p>
                      <ul class="meat__prompt-list">
                        <li class="meat__prompt-item">jpeg, png, gif;</li>
                        <li class="meat__prompt-item">Не более 5 mb;</li>
                        <li class="meat__prompt-item">Минимальный размер 750x450 px.</li>
                      </ul>
                    </section>
                  </button>
                  <input class="visually-hidden jsInputRegistrationParticipantBanner" type="file" id="image" name="image">
                  <label class="meat__placeholder jsContainerDropImage" for="image">Загрузить фотографию
                  <?
                  $sParticipantBannerSrc = $arResult["arParticipantBanner"]["url"] ? $arResult["arParticipantBanner"]["url"] : URL__ORIGINAL_HTML_PATH . "assets/images/holder.svg";
                    
                  
                  ?>
                  <img class="jsImgRegistrationParticipantBanner" src="<?=$sParticipantBannerSrc?>" alt="placeholder"></label>
                </div>
                <div class="meat__buttons"><a class="meat__back jsBtnGoToPrevRegistrationStep" type="button"><span>←</span> Назад</a>
                  <button class="meat__submit" type="submit">Далее<span>→</span></button>
                </div>
              </form>
            </section>
      </div>