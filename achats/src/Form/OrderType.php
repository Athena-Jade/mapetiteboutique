<?php                       //FORMULAIRE USER qui renseigne son ou ses adresses de livraison

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $user=$options['user'];   //récupérer le bon user 
       
        // dd($options);
        $builder
            ->add('addresses', EntityType::class,[
                'label'=>false,
                'required'=>true,
                'class'=>Address::class,    // __toString voir entité address pour récupérer l'adresse, ville, pays
                'choices'=>$user->getAddresses(),  // permet de récupérer l'user avec sa ou ses propre(s) adresse(s)
                'multiple'=> false,
                'expanded'=>true
            ])


        
            ->add('carriers', EntityType::class,[
                'label'=>'Choisissez votre transporteur',
                'required'=>true,
                'class'=>Carrier::class,    //__toString voir entité carrier pour récupérer le nom transporteur, description, prix
                'multiple'=> false,
                'expanded'=>true
            ])
            
            
            ->add('submit', SubmitType::class,[
               'label'=>'Validez votre commande',
               'attr' =>[
                   'class'=>'btn btn-secondary btn-block'
               ]
            ])
            
           
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user'=>array()  //  un tableau
        ]);
    }
}
