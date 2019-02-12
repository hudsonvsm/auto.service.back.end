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

    public function loadPartial($nameFile)
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
}