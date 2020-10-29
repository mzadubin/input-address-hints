<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
 
use Bitrix\Main\Grid\Actions;
use Bitrix\Main\Web; 
use \Bitrix\Main\Data\Cache;
        
        
/**
 * класс компонента тестового задания.
 * выводим инпут и к нему по мере ввода подгружает подсказки по введенному адресу
 */
class CAddressHintComponent extends \CBitrixComponent implements Controllerable
{
    const LOG_TABLE_NAME__GEO_REQUESTS = "bx_mzadubin_testtask_geo_requests_log";
    
    //AJAX ===========================================================
    
    /**
     * указываем какие ajax методы мы принимаем
	 * @return array
	 */
	public function configureActions()
	{
		return [
            //method name
			'processLoadAddressHints' => [
				'prefilters' => [
					//new ActionFilter\Authentication(),
					new ActionFilter\HttpMethod(
						[ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]
					),
					new ActionFilter\Csrf(),
				],
				'postfilters' => []
			],
		];
	}
    
  /**
   * вызывается аяксом встроенным
   * 
   * @param array $post  - поля с клиента:
   * sAddress: '123'
   * 
   * @return json
	 */
	public function processLoadAddressHintsAction(array $post)
	{
        try{
            $arResult = [
                "arHints" => $this->loadHintsByAddressPart($post["sAddress"]),
                "bResult" => true,
            ];
            
        } catch(\Exception $e) {
            $arResult = [
                "bResult" => false,
                "sMessage" => $e->getMessage(),
            ];
        }
        
        return [
            'response' => $arResult["bResult"],
            "arResult" => $arResult,
        ];
      
	}
    
    
  
    //PUBLIC ===========================================================
    /**
     * выполнение компонента начальное
     * 
     * @return bool    
     */
    public function executeComponent()
    {
        if (!$this->checkAndInitReuiredComponentData() ) {
            ShowError($this->lastError);
            return false;
        }
     
        //в данном случае при запуске компонента кеш не нужен тк. мы просто выводим форму
        //$sCacheId = "";
        //if ($this->StartResultCache(false, $sCacheId) ) {
            try {
               
            } catch (\Exception $e) {
                ShowError( $e->getMessage() );
                $this->AbortResultCache();
                return false;
            }
          
           
        //}
        
        $this->includeComponentTemplate();
        return true;
    }
 
  
  
    //PROTECTED ============================================================
    /**
     * проверяем важные параметры перед запуском компонента.
     * например авторизацию пользоватлея и тд
     *
     * @throws Exception - если какого-то важного параметра нет
     * @return bool  
     */
    protected function checkAndInitReuiredComponentData()
    {
        return true;
    }
    
  
    /**
     * получаем из кеша или из удаленнго сервиса список подсказок
     * @param string  $sAddress        запрос адреса
     * 
     * @return array    массив найденных подсказок
     */
    protected function loadHintsByAddressPart(string $sAddress)
    {
        $sClearAddress = trim($sAddress);
        
        if (!strlen($sClearAddress) ) {
            return false;
        }
        
        $obCache = Cache::createInstance();
        $iCacheTime = 7200;
        if ($obCache->initCache($iCacheTime, $sClearAddress) ) { 
            $arHints = $obCache->getVars();
        } elseif ($obCache->startDataCache() ) {
            $arHints = $this->callGeoService($sClearAddress);
            $obCache->endDataCache($arHints);
        }
        
        return $arHints;
    }
   
   
    /**
     * метод обращещния по введеному адресу за подсказками
     * на вншений сервис
     * 
     * @param string  $sAddress        Запрс адреса
     * @param int     $iMaxResultCount количество ответов
     * 
     * @return array    
     */
    protected function callGeoService(string $sClearAddress, int $iMaxResultCount = 6)
    {
        try{
            $obApi = new \Yandex\Geo\Api();
    
            // Или можно икать по адресу
            $obApi->setQuery($sClearAddress);
            
            // Настройка фильтров
            $obApi
                ->setToken(YANDEX_MAP_TOKEN)
                ->setLimit($iMaxResultCount) // кол-во результатов
                ->setLang(\Yandex\Geo\Api::LANG_RU) // локаль ответа
                ->load();
            
            $obResponse = $obApi->getResponse();
            
            $arCollection = $obResponse->getList();
            $arHints = [];
            foreach ($arCollection as $obItem) {
                $arHints[] = $obItem->getAddress();
            }
            $sLogingReponse = implode(", ", $arHints);
            
        } catch (\Exception $e) {
            $sLogingReponse = $e->getMessage();
        }
        
        $this->saveGeoRequestToLog($sAddress, $sLogingReponse);
        
        return $arHints;
    }
   
    /**
     * пишем в самодельную таблицу БД запрос пользователя
     * и ответ от гео сервиса
     * 
     * @param string $sSearchString запрос
     * @param string $sGeoResponse  ответ
     * 
     * @return void    
     */
    protected function saveGeoRequestToLog($sSearchString, $sGeoResponse)
    {
        $obConnection = \Bitrix\Main\Application::getConnection();
        
        $obResult = $obConnection->query(
            "CREATE TABLE IF NOT EXISTS " . self::LOG_TABLE_NAME__GEO_REQUESTS . "(
                id INTEGER NOT NULL auto_increment,
                search_request VARCHAR(256) NOT NULL,
                geo_response TEXT NOT NULL,
                PRIMARY KEY(id)
            )
            ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
        );
        $obConnection->queryExecute(
            "INSERT INTO " . self::LOG_TABLE_NAME__GEO_REQUESTS .
                " (search_request, geo_response)  VALUES ('$sSearchString', '$sGeoResponse')"
        );
    }
   
  
} //end class component 
?>