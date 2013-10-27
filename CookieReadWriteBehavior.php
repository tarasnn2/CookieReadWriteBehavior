<?php

/**
 * Поведение, содержащее вспомогательные функции для массовой установки и чтения кук. Используется в действии admin
 * контроллеров, для сохранения значения фильтрации при обновлении страницы.
 * @param array $cookies массив принимаемых переменных от формы, по которым производиться фильтрация - ключи массива $modelName
 * @param string $modelName название модели - имя массива принимаемого от формы
 */
class CookieReadWriteBehavior extends CBehavior {

    public $cookies;
    public $modelName;

    public function __construct($cookies = null, $modelName = null) {
        $this->cookies = $cookies;
        $this->modelName = $modelName;
    }

    /**
     * массовае чтение кук
     */
    function readCookie() {
        // читаем куку
        foreach ($this->cookies as $cookieName => $cookieValue) {
            $this->cookies[$cookieName] = (isset(Yii::app()->request->cookies[$cookieName]->value)) ?
                    Yii::app()->request->cookies[$cookieName]->value : '';
        }
    }

    /**
     * массовая запись кук
     */
    function writeCookie() {
        foreach ($this->cookies as $cookieName => $cookieValue) {
            Yii::app()->request->cookies[$cookieName] = new CHttpCookie($cookieName, $_GET[$this->modelName][$cookieName]);
        }
    }

    /**
     * массовое присваивание в массив $_GET
     */
    function emulateSendFilters() {
        foreach ($this->cookies as $cookieName => $cookieValue) {
            $_GET[$this->modelName][$cookieName] = $cookieValue;
        }
    }

    /**
     * проверяет, есть ли хоть одна установленная кука в массиве кук
     * @return boolean
     */
    public function issetCookie() {
        foreach ($this->cookies as $cookieValue) {
            if ($cookieValue) {
                return true;
            }
        }
        return false;
    }

    /**
     * манипулируем куками, процесс установки и чтения кук при обновлении админки, повторяемая последовательность.
     * @param string $nameModel
     */
    public function manipulationCookie() {
        $this->readCookie(); // читаем куки
        $request  = Yii::app()->getRequest()->getParam($this->modelName);
        if (isset($request)) {
            $this->writeCookie(); // ставим новую куки
        } 
        else {
            if ($this->issetCookie()) { // если есть хотябы одна установленная кука
                $this->emulateSendFilters(); // эмулируем отправку значения фильтра
            }
        }
    }

}
