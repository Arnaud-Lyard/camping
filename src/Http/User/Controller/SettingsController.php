<?php

namespace App\Http\User\Controller;

use App\Domain\Auth\Entity\User;
use App\Domain\Auth\Form\SettingsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route("/user", name: "user_")]
final class SettingsController extends AbstractController
{
    #[Route("/settings", name: "settings_index", methods: ["GET", "POST"])]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        LocaleSwitcher $localeSwitcher,
    ): Response {
        $user = $this->currentUser();

        $form = $this->createForm(SettingsType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            // Translate the confirmation in the freshly chosen language.
            $localeSwitcher->setLocale($user->getLocale());
            $this->addFlash("success", $translator->trans("settings.flash.saved"));

            return $this->redirectToRoute("user_settings_index");
        }

        return $this->render("user/settings/index.html.twig", [
            "form" => $form,
        ]);
    }

    private function currentUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $user;
    }
}
