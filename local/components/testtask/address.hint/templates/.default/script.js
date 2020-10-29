BX.namespace("BX.MZadubin.Test");

/**
 * JS класс в прототипном стиле
 * реализующий бизнес логику клиентской стороны
 * тестового задания про поле ввода адреса с подсказками.
 *
 * прототтипный стиль исопльзован т.к. в рамках задачи было
 * принято решение выполнить ее в виде компонента битрикс.
 * Для компонента битрикс есть свои особенности:
 * в частности тут нет удобных транслитераторов кода из es6 в es5.
 *
 * Если оформлять задачу как отдельный модуль битрикс,
 * то можно было бы js вынести в "расширения-bitrix" где сборщик битрикса
 * также автоматически прогоняет JS через babel.
 * Также для отдельного модуля битрикс можно было бы воспльзоваться webpack и расположить весь Js
 * как отдельные модули (как и компоненты vue). Но для этой задачи это избыточно. 
 *
 * Использование webpack для компонента битрикс является не типичным
 * (из за особенностей расположения файлов компонента, принятых в данной cms)
 */
(function (window) {
  if (!!BX.MZadubin.Test.JAddressHintPrototype){
    return;
  }
 
  BX.MZadubin.Test.JAddressHintPrototype = function()
  {
    //флаг показывающий делается ли в данный момент ajax запрос
    this.bAjaxLoading = false;
    //для хранения введенных фраз и их результатов, уже полученных от сервера
    this.obCachedData = {};
    
    this._start();
  };
  
  /**
   * действия при инициализации объекта
   * рисуем форму и слушаем ее
   * 
   * @returns void
   */
  BX.MZadubin.Test.JAddressHintPrototype.prototype._start = function()
  {
    this._initFormComponents();
    this._drawForm();
    this._subscribeOnFormEvents();
  }; 
 
 
  /**
   * слушаем текуим классом события Vue приложения
   * чтобы делать запросы к серверу и тд (выносим это из функционала компонентов vue
   * т.к. у битрикса есть свои механизмы для ajax вместо axios от vue)
   * 
   * @returns void
   */
  BX.MZadubin.Test.JAddressHintPrototype.prototype._subscribeOnFormEvents = function()
  {
    var _this = this;
    
    //слушаем события когда надо подгрузить адрес
    var processLoadAddressHints = this._processLoadAddressHints.bind(_this);
    BX.Vue.event.$on('bx-addresss-input:need-load-hint', processLoadAddressHints);
  };
  
  /**
   * просто создаем на лету компоненты vue дял отрисовки приложения:
   * инпут и подсказки к нему
   * 
   * @returns void
   */
  BX.MZadubin.Test.JAddressHintPrototype.prototype._initFormComponents = function()
  {
    /**
     * подсказки с адресами для инпута. данные прилетают с сервера через событие
     * @listens 'bx-addresss-input:hints-loaded' {} (global)
     */
    BX.Vue.component('bx-address-hints-list', 
    {
      data: function()
      {
        return {
            arItems: []
        }
      },
      created: function()
      {
          BX.Vue.event.$on('bx-addresss-input:hints-loaded', this.refreshHintsList);
      },
      beforeDestroy: function()
      {
          BX.Vue.event.$off('bx-addresss-input:hints-loaded', this.refreshHintsList);
      },
      methods: {
        refreshHintsList: function(obEvent)
        {
          var _this = this;
          this.arItems = [];
          setTimeout(function(){
            _this.arItems = obEvent.arHints;
          }, 200);
        }
      },
      template: '\
        <datalist id="address-suggestion-list" >\
          <option v-for="sText in arItems" :value="sText" >\
            {{ sText }}\
          </option>\
        </datalist>\
      '
    });
    
    /**
     *  инпут ввода адреса с подсказками от сервера
     *  @emits 'bx-addresss-input:need-load-hint', {sAddress: string }
     *  @listens 'bx-addresss-input:hints-loaded' {} (global)
     */
    BX.Vue.component('bx-address-input', 
    {
      props: {
        obParams: { type: Object, required: false, default: {iMinAddressLength: 3 } },
      },
      data: function()
      {
        return {
            sAddress: '',
            bIsReadonly: false
        }
      },
      created: function()
      {
          BX.Vue.event.$on('bx-addresss-input:hints-loaded', this.unblockTypeAccess);
      },
      beforeDestroy: function()
      {
          BX.Vue.event.$off('bx-addresss-input:hints-loaded', this.unblockTypeAccess);
      },
      watch: {
        sAddress: function(sVal, sOldVal) {
          if (
            sVal === sOldVal
            ||
            sVal.length < this.obParams.iMinAddressLength
          ) {
            return false;
          }
          
          this.bIsReadonly = true;
    
          BX.Vue.event.$emit('bx-addresss-input:need-load-hint', {sAddress: sVal.trim() } );
        }
      },
      methods: {
        onSpace: function(obEvent)
        {
          //запрещаем пробелы вначале и конце.
          if (this.sAddress.trim() === "" || this.sAddress.slice(-1) === " ") {
            obEvent.preventDefault();
          }
        },
        onConfirm: function(obEvent)
        {
          obEvent.preventDefault();
          obEvent.stopPropagation();
          alert("confirm");
        },
        unblockTypeAccess: function()
        {
          this.bIsReadonly = false;
        }
      },
      template: '\
        <form>\
          <input\
              autocomplete="off"\
              v-bind:readonly="bIsReadonly"\
              type="text"\
              v-model="sAddress"\
              v-on:keyup.enter="onConfirm"\
              v-on:keydown.space="onSpace"\
              class="input-address"\
              list="address-suggestion-list"\
              placeholder="Введите адрес"\
          />\
          <bx-address-hints-list/>\
        </form>\
      ',
    });
    
  };
  
  /**
   * рисует приложение vue.
   * в случае необходимости селектор контейнера можно передавать через параметры
   * 
   * @returns void
   */
  BX.MZadubin.Test.JAddressHintPrototype.prototype._drawForm = function()
  {
    BX.Vue.create({ 
        el: '#test-task-application',
        data: function()
        {
            return {
                obParams: {
                    iMinAddressLength: 3
                },
            }
        },
        template: '\
            <bx-address-input \
                :obParams="obParams"\
            />\
        ' 
    });
  };

  
  /**
   * загружаем подсказки с сервера
   * испольузем стандартный механизм аякс запросов bitrix для компонентов
   * 
   * @param  object obData - поля передаваемые на сервер.
   * обязательным является поле string sAddress
   *
   * @emits 'bx-addresss-input:hints-loaded', {arHints: array }
   * 
   * @returns void
   */
  BX.MZadubin.Test.JAddressHintPrototype.prototype._processLoadAddressHints = function(obData)
  {
    var _this = this;
    
    if (obData.sAddress in this.obCachedData) {
      BX.Vue.event.$emit('bx-addresss-input:hints-loaded', {arHints: this.obCachedData[obData.sAddress] } );
    }
    
    var obRequest = BX.ajax.runComponentAction('testtask:address.hint', 'processLoadAddressHints', {
      mode:'class',
      data: {
        sessid: BX.message('bitrix_sessid'),
        post: obData
      }
    }).then(function(obResponse) {
      
      if (obResponse.data.arResult.bResult === true) {
        _this.obCachedData[obData.sAddress] = obResponse.data.arResult.arHints;
        
        BX.Vue.event.$emit('bx-addresss-input:hints-loaded', {arHints: obResponse.data.arResult.arHints} );
      } else{
        alert("Что-то пошло не так: " + obResponse.data.arResult.sMessage)
      }
      
    }).catch(function(obResponse){
      var sErrorMsg = "";
      Array.prototype.forEach.call(obResponse.errors, function(obRow){    
        sErrorMsg += obRow.code + " - " + obRow.message + "\n\r";
      });
      
      alert("Ошибка: " + sErrorMsg);
    });
  };
 
  
  
  
})(window);  