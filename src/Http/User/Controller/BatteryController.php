<?php

namespace App\Http\User\Controller;

use App\Domain\Auth\Entity\User;
use App\Domain\Equipment\Entity\Battery;
use App\Domain\Equipment\Form\BatteryType;
use App\Domain\Equipment\Repository\BatteryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route("/user", name: "user_")]
final class BatteryController extends AbstractController
{
    private const DEFAULT_FREQUENCY = 30;

    #[Route("/battery", name: "battery_index", methods: ["GET", "POST"])]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        BatteryRepository $repository,
        TranslatorInterface $translator,
    ): Response {
        $user = $this->currentUser();
        $battery = $repository->findOneBy(["owner" => $user])
            ?? (new Battery())
                ->setOwner($user)
                ->setIsActive(false)
                ->setFrequency(self::DEFAULT_FREQUENCY);

        $form = $this->createForm(BatteryType::class, $battery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTimeImmutable();
            if (null === $battery->getId()) {
                $battery->setCreatedAt($now);
            }
            $battery->setUpdatedAt($now);

            // Start the countdown when the reminder is first enabled.
            if ($battery->isActive() && null === $battery->getLastReminderAt()) {
                $battery->setLastReminderAt($now);
            }

            $em->persist($battery);
            $em->flush();

            $this->addFlash("success", $translator->trans("battery.flash.saved"));

            return $this->redirectToRoute("user_battery_index");
        }

        return $this->render("user/battery/index.html.twig", [
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
