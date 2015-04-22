<?php

namespace LiteApplication\View;


use LiteApplication\Http\Routing\Router;
use LiteApplication\Service\Service;

/**
 * @Service(name="view.renderer", arguments=['@router', '%root_dir'])
 */
class ViewRenderer {

    /**
     * @var ViewBag
     */
    private $viewBag;

    /**
     * @var string
     */
    private $rootDir;


    function __construct(Router $router, $rootDir) {
        $this->viewBag = new ViewBag($router);
        $this->rootDir = $rootDir;
    }

    public function render($templateFile, $controller = null, $vars = []) {
        $path = $this->findFile($controller, $templateFile);


        extract(array_merge($vars, ['viewBag' => $this->viewBag]));

        ob_start();
        include $path;
        $body = ob_get_clean();

        return $body;
    }

    private function findFile($controller, $templateFile) {

        // it means the path is absolute
        if (0 === strpos($templateFile, '/')) {
            $path = $this->rootDir . $templateFile;
        }
        else  $path = sprintf("%s/app/View/%s/%s.php", $this->rootDir, $controller, $templateFile);

        if (!is_file($path)) {
            throw new ViewException("Resource view not found at %s", $path);
        }
        return $path;
    }

}
