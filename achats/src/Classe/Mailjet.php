<?php                                       ######## CREATION DE CLASSE MAILJET afin de bien gérer mes mails ##############"

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;




class Mail
{
    private $api_key = 'a6a8302c9a6d1da9bb7f29bf83cacbf5';

    private $api_secret = '7ba8da5f3d347c4b188cb7cba07a2947';

    
    public function send($to_email, $to_name, $subject, $content)
    {

        $mj = new Client($this->api_key, $this->api_secret, true,['version' => 'v3.1']); //création nouveau mail

        $mj = new \Mailjet\Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'),true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                    [
                        'From' => [
                            'Email' => "27mapetiteboutique72@gmail.com",
                            'Name' => "Ma Petite Boutique"
                        ],
                        'To' => [
                            [
                                'Email' => "$to_email",
                                'Name' => "$to_name"
                            ]
                        ],
                        'TemplateID' => 2936535,
                        'TemplateLanguage' => true,
                        'Subject' => $subject,
                        'Variables' => [
                            'content' => $content,
                            
                        ]
                    ]
               
                
            ]
       
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dd($response->getData());

    }          

}