CookieReadWriteBehavior
=======================

YII Behavior

Поведение, содержащее вспомогательные функции для массовой установки и чтения кук. Используется в действии admin контроллеров, для сохранения значения фильтрации при обновлении страницы.
@param array $cookies массив принимаемых переменных от формы, по которым производиться фильтрация - ключи массива $modelName
@param string $modelName название модели - имя массива принимаемого от формы


Подключение к модели:

    public function behaviors() {
        return array(
            'CookieReadWriteBehavior' => array(
                'class' => 'CookieReadWriteBehavior',
                'cookies' => array(
                    'relOrg_name_full' => '',
                    'idDocsCertificate_number_certificate' => '',
                    'idDocsProtocol_number' => '',
                    'idDocsExpertReport_number' => '',
                    'idDocsRequest_number' => '',
                    'idDocsVerification_number' => '',
                ),
                'modelName' => 'Docs',
            ),
        );
    }


Пример использованиея:

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Docs('search');
        $model->unsetAttributes();
        $this->CookieReadWriteBehavior->manipulationCookie();
        $model->attributes = $_GET['Docs'];
        $this->render('admin', array(
            'model' => $model,
        ));
    }
    
    
