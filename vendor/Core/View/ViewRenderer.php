<?php

namespace vendor\Core\View;


use vendor\Core\Routing\Router;


/**
 * @Service(name="view.renderer", arguments=['@router'])
 */
class ViewRenderer {

    /**
     * @var Router
     */
    private $router;

    function __construct(Router $router)
    {
        $this->router = $router;
    }


    public function render($templateFile, $controller = null, $vars = []) {
        $path = $this->findFile($controller, $templateFile);

        extract($vars);

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

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }






}
