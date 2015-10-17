<?php

namespace ImageFaker\Controller;

use ImageFaker\Config\Config;
use ImageFaker\Config\Size;
use ImageFaker\Entity\Input;
use ImageFaker\Form\ImageType;
use Silex\Application;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Based on https://igor.io/2012/11/09/scaling-silex.html
 *
 * Class BaseController
 * @package ImageFaker\Controller
 */
class BaseController
{
    public function indexAction(Application $app, Request $request)
    {
        $form = $this->createForm($app);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            try {
                return $this->redirectToImage($app, $form);
            } catch (\Exception $e) {
                $app['session']->getFlashBag()->add('message', 'The data is incorrect');
            }
        }

        return new Response(
            $app['twig']->render("homepage.twig", array(
                "config" => new Config(new Size(1, 1)),
                "sizes"  => (array)$app["image_faker.sizes"](),
                "form"   => $form->createView(),
            )),
            200,
            array()
        );
    }

    private function redirectToImage(Application $app, FormInterface $form)
    {
        /* @var Input $input */
        $input = $form->getData();
        $data = array(
            "size" => $input->getSize(),
            "extension" => $input->getExtension(),
        );
        //$config = $this->createConfig($app, $data);
        $config = $input->createConfig();
        $route = "simple";
        if ($input->getBackground()) {
            $data["background"] = ltrim($input->getBackground(), "#");
            $route = "background";
        }
        if ($input->getColor()) {
            $data["color"] = ltrim($input->getColor(), "#");
            $route = "font";
            if (!isset($data["background"])) {
                $data["background"] = ltrim((string)$config->getBackgroundColor(), "#");
            }
        }

        return $app->redirect(
            $app["url_generator"]->generate($route, $data)
        );
    }

    /**
     * @param Application $app
     * @param array $data
     * @return Config
     */
    private function createConfig(Application $app, $data = array())
    {
        $values = array(
            "size"              => isset($data["size"]) ? $data["size"] : "",
            'background_color'  => isset($data["background"]) ? $data["background"] : "",
            'color'             => isset($data["color"]) ? $data["color"] : "",
            'extension'         => isset($data["extension"]) ? $data["extension"] : "",
        );
        $input = new Input($app["image_faker.parameters"]);
        $input->setSize($values["size"]);
        $input->setExtension($values["extension"]);
        $input->setBackground($values["background_color"]);
        $input->setColor($values["color"]);

        return $input->createConfig();
    }

    /**
     * @param Application $app
     * @return Form
     */
    private function createForm(Application $app)
    {
        return $app['form.factory']->createBuilder(new ImageType())->getForm();
    }

    public function imageAction(Application $app, Request $request)
    {
        $config = $this->createConfig($app, $request->attributes->get("_route_params"));

        return $app["image_faker.response"]($config, $request);
    }
}
