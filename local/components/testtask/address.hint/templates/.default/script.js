BX.namespace("BX.MZadubin.Test");

(function (window) {
  if (!!BX.MZadubin.Test.JAddressHintPrototype){
    return;
  }
 
  BX.MZadubin.Test.JAddressHintPrototype = function()
  {
    this.bAjaxLoading = false;
    this.obCachedData = {};
    
    this._start();
  };
  
  BX.MZadubin.Test.JAddressHintPrototype.prototype._start = function()
  {
    this._initFormComponents();
    this._drawForm();
    this._subscribeOnFormEvents();
  }; 
 
 
  BX.MZadubin.Test.JAddressHintPrototype.prototype._subscribeOnFormEvents = function()
  {
    var _this = this;
    
    //слушаем события когда надо подгрузить адрес
    var processLoadAddressHints = this._processLoadAddressHints.bind(_this);
    BX.Vue.event.$on('bx-addresss-input:need-load-hint', processLoadAddressHints);
  };
  
  
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
          this.arItems = obEvent.arHints;
        }
      },
      template: '\
        <datalist id="address-suggestion-list" >\
          <li v-for="sText in arItems" >\
            <option> {{ sText }} </option>\
          </li>\
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
        sAddress: function(sVal) {
          if (
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

  
  BX.MZadubin.Test.JAddressHintPrototype.prototype._processLoadAddressHints = function(obEvent)
  {
    var _this = this;
    
    if (obEvent.sAddress in this.obCachedData) {
      BX.Vue.event.$emit('bx-addresss-input:hints-loaded', {arHints: this.obCachedData[obEvent.sAddress] } );
    }
    
    var obRequest = BX.ajax.runComponentAction('testtask:address.hint', 'processLoadAddressHints', {
      mode:'class',
      data: {
        sessid: BX.message('bitrix_sessid'),
        post: obEvent
      }
    }).then(function(obResponse) {
      
      if (obResponse.data.arResult.bResult === true) {
        _this.obCachedData[obEvent.sAddress] = obResponse.data.arResult.arHints;
        
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