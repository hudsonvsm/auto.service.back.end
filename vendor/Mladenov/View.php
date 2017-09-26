<?php
/**
 * @author V.Mladenov
 */
namespace Mladenov;

/**
 * Class View
 * @package Mladenov
 */
class View
{
    private $content;
    protected $templatePath;
    private $response;

    /**
     * @return array
     */
    public function getResponse() : array
    {
        return $this->response;
    }

    public function __construct(string $folderName, string $fileName, array $response)
    {
        $this->response = $response;

        $this->templatePath = Config::getProperty('templatePath')
            . strtolower($folderName)
            . DIRECTORY_SEPARATOR
            . strtolower($fileName)
            . '.phtml';
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
}