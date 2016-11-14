<?php

namespace SoftUniBlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SoftUniBlogBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AdminController
 * @Security("is_granted('ROLE_ADMIN')")
 * @package SoftUniBlogBundle\Controller
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin/users/list", name="admin_users_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUsers()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render("admin/users/list.html.twig", ["users"=> $users]);
    }

    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }
}
