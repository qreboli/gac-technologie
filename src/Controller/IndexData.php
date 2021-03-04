<?php

namespace App\Controller;

use App\Repository\CommunicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class IndexData extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/data")
     */
    public function index(CommunicationRepository $communicationRepository)
    {
        //dd($communicationRepository->findAllSms());
        return new Response($this->twig->render("home/index.html.twig", [
        'real_duration' => $communicationRepository->findRealDurationCallsAfterDate(),
            'billed_duration' => $communicationRepository->findBilledDuration(),
            'smsCount' => $communicationRepository->findAllSms()
        ]));
    }

}
