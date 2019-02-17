<?php
/**
 * @author V.Mladenov
 */
namespace Mladenov;

use App\Model\I18N;
use App\Router;

/**
 * Class View
 * @package Mladenov
 */
class View
{
    private $content;
    protected $templatePath;
    private $response;
    private $uiLocale = array();
    private $localization;

    /**
     * @return array
     */
    public function getResponse() : array
    {
        return $this->response;
    }

    public function __construct(string $folderName, string $fileName, array $response)
    {
        $db = Config::getProperty('db');

        $dbDriver = $db['dbDriver'];

        $dbTableColumns = Config::getProperty('tables');

        $this->localization = new I18N($dbDriver::getInstance($db), DB_TABLE_I18N, $dbTableColumns[DB_TABLE_I18N]);

        $this->response = $response;

        $this->templatePath = Config::getProperty('templatePath')
            . strtolower($folderName)
            . DIRECTORY_SEPARATOR
            . strtolower($fileName)
            . '.phtml';
    }

    public function localizeUi(array $fields) : void
    {
        $fields = array_keys(array_diff_key(array_flip($fields), $this->uiLocale));

        if (count($fields) == 0) return;

        $result = $this->localization->getLocalization(Router::$lang, $fields);

        $this->uiLocale += $result;
    }

    public function render()
    {
        if (file_exists($this->templatePath)) {
            ob_start();
            include_once $this->templatePath;
            $this->content = ob_get_clean();
        } else {
            throw new \Exception('File template does not exists: ' . $this->templatePath);
        }

        $this->tryLoadMasterLayout();
    }

    private function tryLoadMasterLayout()
    {
        $file = Config::getProperty('masterLayout');

        if (file_exists($file)) {
            include_once $file;
        } else {
            return $this->content;
        }
    }

    public function loadPartial($nameFile, string $modalId = null)
    {
        $file = Config::getProperty('templatePath') . DIRECTORY_SEPARATOR . 'helper' . DIRECTORY_SEPARATOR . $nameFile .'.phtml';

        if (file_exists($file)) {
            include $file;
        }
    }

    function camelCaseToHyphen(string $string) {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $string));
    }

    /**
     * @return array
     */
    public function getUiLocale(): array
    {
        return $this->uiLocale;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getUiLocaleField(string $key) : string
    {
        return $this->uiLocale[$key];
    }
}