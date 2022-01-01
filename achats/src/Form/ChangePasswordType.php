<?php                           //formulaire pour user qui change son mot de passe

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'disabled' => true,    // disabled permet de bloquer l'user qui ne peut pas changer son adresse email
                'label' => 'Votre adresse email'
            ])
          
           
            ->add('firstname', TextType::class,[
                'disabled'=>true,
                'label' => 'Votre prénom'
            ])
            ->add('lastname', TextType::class,[
                'disabled'=>true,
                'label' =>'Votre nom'
            ])
        
            
            ->add('old_password', PasswordType::class,[
                'mapped' =>false,    // old_password n'existe pas dans entity user donc j'ajoute 'mapped false'
                'label' => 'Votre mot de passe actuel',
                'attr' =>[
                    'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                ]
            ])
        


            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' =>false,    // new_password n'existe pas dans entity user donc j'ajoute 'mapped false'
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => [
                    'label' => 'votre nouveau mot de passe',
                    'attr'=> [
                        'placeholder'=>'Merci de saisir votre nouveau mot de passe'
                    ]
                ],

                'second_options' => [
                    'label' => 'Confirmer votre nouveau mot de passe',
                    'attr' =>[
                        'placeholder'=>'Merci de confirmer votre nouveau mot de passe'
                    ]
                ],
           
                ])
        

            ->add('submit', SubmitType::class,[
                    'label'=>'Mettre à jour',
                    'attr'=>[
                        'class'=> 'btn block btn-secondary'
                    ]
            ])


        ;

    
    
    
    }

    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
