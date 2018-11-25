<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends Controller
{
    /**
     * @Route("/blog/list", name="blog_list")
     */
    public function listAction(Request $request)
    {
        return $this->render('blog_actions/list.html.twig');
    }
    /**
     * @Route("/blog/edit/{id}", name="blog_edit")
     */
    public function editxAction(Request $request)
    {
        return $this->render('blog_actions/edit.html.twig');
    }
    /**
     * @Route("/blog/details/{id}", name="blog_details")
     */
    public function detailsAction(Request $request)
    {
        return $this->render('blog_actions/details.html.twig');
    }
    /**
     * @Route("/blog/delete/{id}", name="blog_delete")
     */
    public function deleteAction(Request $request)
    {
        return $this->render('blog_actions/delete.html.twig');
    }
}
