<?php

namespace ImageFaker\Controller;

use ImageFaker\Config\Config;
use ImageFaker\Config\Size;
use ImageFaker\Config\SizeFactory;
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

            return $this->redirectToImage($app, $form);
        }

        return new Response(
            $app['twig']->render("homepage.twig", array(
                "config"        => new Config(new Size(1, 1)),
                "defaultSizes"  => SizeFactory::$defaultSizes,
                "form"          => $form->createView(),
            )),
            200,
            array()
        );
    }

    private function redirectToImage(Application $app, FormInterface $form)
    {
        $data = $form->getData();

        return $app->redirect(
            $app["url_generator"]->generate("simple", $data)
        );
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
        $values = array(
            "size"              => $request->get("size"),
            'background-color'  => $request->get("background"),
            'color'             => $request->get("color"),
            'extension'         => $request->get("extension"),
        );
        $config = $app["image_faker.config"]($values);

        return $app["image_faker.response"]($config, $request);
    }
}
