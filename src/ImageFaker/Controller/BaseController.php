<?php

namespace ImageFaker\Controller;

use ImageFaker\Config\Config;
use ImageFaker\Config\Size;
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
        $data = $form->getData();
        $config = $this->createConfig($app, $data);
        $route = "simple";
        if (isset($data["background"]) && $data["background"]) {
            $data["background"] = ltrim($data["background"], "#");
            $route = "background";
        }
        if ($data["color"]) {
            $data["color"] = ltrim($data["color"], "#");
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
            'background-color'  => isset($data["background"]) ? $data["background"] : "",
            'color'             => isset($data["color"]) ? $data["color"] : "",
            'extension'         => isset($data["extension"]) ? $data["extension"] : "",
        );

        return $app["image_faker.config"]($values);
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
