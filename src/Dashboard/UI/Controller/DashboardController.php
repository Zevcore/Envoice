<?php
declare(strict_types=1);

namespace App\Dashboard\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/dashboard', 'app_dashboard')]
    public function index() {

    }
}