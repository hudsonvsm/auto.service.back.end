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
    private $data;

    public function __construct(string $folderName, string $fileName, array $data)
    {
        $this->data = $data;

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
            echo $this->content;
        }
    }

    public function loadPartial($nameFile)
    {
        $file = Config::getProperty('templatePath') . DIRECTORY_SEPARATOR . 'helper' . DIRECTORY_SEPARATOR . $nameFile .'.phtml';

        if (file_exists($file)) {
            include $file;
        }
    }
}