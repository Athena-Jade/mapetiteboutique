<?php                                                                       ##   J'ai reçu une confirmation de succes mail d'emailjet mais sur mon compte gmail je n'ai pas reçu le mail test ... problème avec Gmail ####

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Classe\Mail;                                        # #  Ce controller me permet d'envoyer des mails aux users. politique confidentialité de Google bloque la réception. Sur Mailjet le mail est bien envoyé    # #

class TestMailController extends AbstractController
{
    #[Route('/mail', name: 'test_mail')]
    public function index(): Response
    {
        
        
       $mail = new Mail();

        $mail->send('27mapetiteboutique72@gmail.com', 'Titi', 'mon premier mail test', 'test mail'); // OK AFFICHAGE && dd($response->getData());     
        
        return $this->render('test_mail/index.html.twig', [
            'controller_name' => 'TestMailController',
        ]);
    
    
        dd($response->getData());
    }

}
