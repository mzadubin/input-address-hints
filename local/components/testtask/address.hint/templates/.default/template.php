<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();
$this->addExternalJS("https://cdnjs.cloudflare.com/ajax/libs/es5-shim/4.5.14/es5-shim.min.js");
\Bitrix\Main\UI\Extension::load("ui.vue");
CUtil::InitJSCore();
?>
<div id="test-task-application"></div>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(){
        try{
            var obAddressHints = new BX.MZadubin.Test.JAddressHintPrototype(
                <? echo CUtil::PhpToJSObject([], false, true); //если нужно передать что-то с php в Js объект?>
            );
        } catch(e) {
            console.error("error address hints: " + e);
        }
    });
</script>

