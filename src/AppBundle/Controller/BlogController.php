<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Trsteel\CkeditorBundle\Form\Type\CkeditorType;

class BlogController extends Controller
{
    /**
     * @Route("/blog/list", name="blog_list")
     */
    public function listAction(Request $request)
    {
        $blog = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->findAll();

        /**
         * @var $paginator \Knp\Component\Pager\Paginator"
         */

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate($blog,
                             $request->query->getInt('page', 1),
                             $request->query->getInt('limit', 3)
        );

        return $this->render('blog_actions/list.html.twig', array( 'blog' =>$result));
    }
    /**
     * @Route("/blog/edit/{id}", name="blog_edit")
     */
    public function editAction(Request $request)
    {

        return $this->render('blog_actions/edit.html.twig');
    }
    /**
     * @Route("/blog/new", name="blog_new")
     */
    public function newAction(Request $request)
    {
        $blog= new Blog;
        $form=$this->createFormBuilder($blog)
            ->add('title', TextType::Class, array('attr' => array('class'=>'form-control')))
            ->add('author', TextType::Class, array('attr' => array('class'=>'form-control')))
            ->add('publicationDate', DateTimeType::Class, array('attr' => array('class'=>'form-control')))
            ->add('content', CkeditorType::class, array(
                'transformers'                 => array('html_purifier'),
                'toolbar'                      => array('document','basicstyles', 'styles'),
                'toolbar_groups'               => array(
                    'document' => array('Source')
                ),
                'ui_color'                     => '#fff',
                'startup_outline_blocks'       => false,
                'width'                        => '100%',
                'height'                       => '320',
                'language'                     => 'en-au',
                'filebrowser_image_browse_url' => array(
                    'url' => 'relative-url.php?type=file',
                ),
            ))
            ->add('imageUrl', TextType::Class, array('attr' => array('class'=>'form-control')))
            ->add('shortedContent', TextType::Class, array('attr' => array('class'=>'form-control')))
            ->add('save', SubmitType::Class, array('label'=>'Create Post', 'attr' => array('class'=>'form-control')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $title=$form['title']->getData();
            $author=$form['author']->getData();
            $publicationDate=$form['publicationDate']->getData();
            $content=$form['content']->getData();
            $imageUrl=$form['imageUrl']->getData();
            $shortedContent=$form['shortedContent']->getData();

            $blog->setTitle($title);
            $blog->setAuthor($author);
            $blog->setPublicationDate($publicationDate);
            $blog->setContent($content);
            $blog->setImageUrl($imageUrl);
            $blog->setShortedContent($shortedContent);

            $temp = $this->getDoctrine()->getManager();
            $temp->persist($blog);
            $temp->flush();

            $this->addFlash(
                'notice',
                      'Blog Added'
            );

            return $this->redirectToRoute('http://127.0.0.1:8000/blog/list');

        }

        return $this->render('blog_actions/new.html.twig', array('form'=>$form->createView()));
    }
    /**
     * @Route("/blog/details/{id}", name="blog_details")
     */
    public function detailsAction($id)
    {
        $blog = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->find($id);

        return $this->render('blog_actions/details.html.twig', array( 'blog' =>$blog));
    }
    /**
     * @Route("/blog/delete/{id}", name="blog_delete")
     */
    public function deleteAction(Request $request)
    {

        return $this->render('blog_actions/delete.html.twig');
    }
}
