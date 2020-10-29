BX.namespace("Corners5.Project.AddTenderProject.AddTenderProject");

/**
 * общие меотды для шага. чтобы унаследоваться от него
 */
(function() {
  if (!!BX.Corners5.Project.AddTenderProject.JStepGeneralPrototype){
    return;
  }
  
  
  BX.Corners5.Project.AddTenderProject.JStepGeneralPrototype = function(arParams)
  {
    //наследуемся от обработчика разных форм
    BX.Corners5.Project.AbstractForm.apply(this, arguments);
    
    if (!arParams) {
      throw "params not specified";
    }
    
    
    //получаем HTML шага с серера(отдельным запрсоом)
    this.processLoadHtmlTemplate = function(sHtmlTemplateName, sObjectType)
    {
      var _this = this;
      
      return new Promise(function(success, fail) {
        var obData = {
          sHtmlTemplateName: sHtmlTemplateName,
          sObjectType: sObjectType,
        };
        
        console.log("send server data = ");
        console.dir(obData);
        
        var obRequest = BX.ajax.runComponentAction('corners5:add.tender-project', 'processLoadHtmlTemplate', {
            mode:'class',
            data: {
              sessid: BX.message('bitrix_sessid'),
              post: obData
            }
        }).then(function(response) {
          if (response.data.arResult.bResult === true) {
            return success(response.data.arResult.sHtml);
            
          } else{
            throw "error load step template: " + response.data.arResult.sMessage;
          }
          
          
        }).catch(function(response){
          var sErrorMsg = "";
          if (typeof response === "string") {
            console.error("step not created: " + response);
          } else{
            $(response.errors).each(function(){
              sErrorMsg += this.code + " - " + this.message + "\n\r";
            });
            console.error("error ajax call get step html: " + sErrorMsg);
            //return fail(sErrorMsg);
            
          }
        });
      });
    };
  
    
  };
  
  

})(window);





