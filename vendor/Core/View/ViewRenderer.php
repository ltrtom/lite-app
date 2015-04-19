<?php

namespace vendor\Core\View;


use vendor\Core\Routing\Router;
use vendor\Core\App\Service\Service;


/**
 * @Service(name="view.renderer", arguments=['@router'])
 */
class ViewRenderer {


    /**
     * @var ViewBag
     */
    private $viewBag;

    function __construct(Router $router)
    {
        $this->viewBag = new ViewBag($router);
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
            $path = ROOT_DIR . $templateFile;
        }
        else  $path = sprintf("%s/app/View/%s/%s.php", ROOT_DIR, $controller, $templateFile);

        if (!is_file($path)) {
            throw new ViewException("Resource view not found at %s", $path);
        }
        return $path;
    }



}
