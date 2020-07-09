<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Champ pseudonyme
            ->add('pseudonym', TextType::class, [
                'label' => 'Pseudonyme',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un pseudonyme',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Votre pseudonyme doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre pseudonyme doit contenir au maximum {{ limit }} caractères',
                    ]),
                ]
            ])
            // Champ prénom
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Jean'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un prénom',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Votre prénom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre prénom doit contenir au maximum {{ limit }} caractères',
                    ]),
                ]
            ])
            // Champ nom
            ->add('lastname', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Dupont'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un nom',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Votre nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre nom doit contenir au maximum {{ limit }} caractères',
                    ]),
                ]
            ])
            // Champ télephone
            ->add('phoneNumber', TextType::class, [
                'label' => 'Télephone',
                'attr' => [
                    'placeholder' => '+330999999999',
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 17,
                        'minMessage' => 'Le numéro doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le numéro doit contenir au maximum {{ limit }} caractères',
                    ]),
                    new NotBlank([
                        'message' => 'Merci de renseigner votre numéro de télephone'
                    ]),
                    new Regex([
                        'pattern' => '/^([0-9]{2}[-.]?){4}[0-9]{2}$/',
                        'message' => 'Votre numéro de télephone doit être un numéro valide'
                    ]),
                ]
            ])
            // Champ genre
            ->add('gender', ChoiceType::class, [
                'label' => 'Vous êtes...',
                'choices'  => [
                    '-Aucun-' => null,
                    'Homme' => true,
                    'Femme' => false,
                ],
                'choice_label' => function($choice, $key, $value) {
                    if (true === $choice) {
                        return 'Homme';
                    }

                    return strtoupper($key);
                },
            ])
            // Champ email
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'placeholder' => 'bourguibook@exemple.com'
                ],
                'constraints' => [
                    new Email([
                        'message' => 'L\'adresse email {{ value }} n\'est pas une adresse valide'
                    ]),
                    new NotBlank([
                        'message' => 'Merci de renseigner une adresse email'
                    ]),
                ]
            ])
            // Champ mot de passe (en double avec sa confirmation)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe ne correspond pas à sa confirmation',
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'max' => 4096,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre mot de passe est trop grand',
                    ]),
                    new Regex([
                        'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[ !"\#$%&\'\(\)*+,\-.\/:;<=>?@[\\^\]_`\{|\}~])^.{8,4096}$/',
                        'message' => 'Votre mot de passe doit contenir au moins une miniscule, une majuscule, un chiffre et un caractère spécial'
                    ]),
                ]
            ])
            // Champ conditions
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            //Bouton de validation
            ->add('save', SubmitType::class, [
                'label' => 'Créer mon compte',
                'attr' => [
                    'class' => 'btn btn-outline-secondary col-12'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            //TODO: à virer
            'attr' => [
                'novalidate' => 'novalidate'
            ],
        ]);
    }
}
