<?php
namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @Route("/contact", name="contact")
     */
    public function index (Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();
            $message = (new \Swift_Message('Vous avez reçu un mail'))
                ->setFrom($contactFormData['fromEmail'])
                ->setTo('email@domain.nettt')
                ->setBody($contactFormData['message'], 'text/plain');
            $mailer->send($message);
            $this->addFlash('success', 'Message bien envoyé');
            return $this->redirectToRoute('contact');
        }
        return $this->render('contact/index.html.twig', [
            'email_form' => $form->createView()
        ]);
    }
}