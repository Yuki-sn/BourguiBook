<?php

namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTime;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;

class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email *',
                'constraints' => [
                    new Email([
                        'message' => 'L\'adresse email {{ value }} n\'est pas une adresse valide'
                    ]),
                    new NotBlank([
                        'message' => 'Merci de renseigner une adresse email'
                    ]),
                ]
            ])

            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone *',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un numéro de téléphone'
                    ]),
                    new Regex([
                        'pattern' => "/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/",
                        'message' => 'Votre numéro est incorrect '
                    ]),
                ]
            ])
            ->add('siret', TextType::class, [
                'label' => 'Votre numéro de siret *',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un numéro de siret'
                    ]),
                    new Regex([
                        'pattern' => "/[0-9]{14}$/",
                        'message' => 'Votre  numéro de siret ne comporte pas 14 chiffres '
                    ])
                ]
            ])
            ->add('typeActivity', ChoiceType::class, [
                'label' => 'le type d\'activité *',
                'choices'  => [
                    'hotel' => 'hotel',
                    'restaurant' => 'restaurant',
                    'autre' => 'autre',
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre *',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un titre'
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
                        'max' => 60,
                        'maxMessage' => 'Le titre doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville *',
                'constraints' => [

                    new NotBlank([
                        'message' => 'Merci de renseigner une ville'
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Le titre doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal *',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un mot de passe'
                    ]),
                    new Regex([
                        'pattern' => "/^[0-9]{5}$/",
                        'message' => 'Votre code postal est incorrect '
                    ]),
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Address *',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner une ville'
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
                        'max' => 100,
                        'maxMessage' => 'Le titre doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description de l\'activite *',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner une ville'
                    ]),

                    new Length([
                        'min' => 1,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères',
                        'max' => 2000,
                        'maxMessage' => 'Le titre doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])

            ->add('startDate', DateType::class, [
                'label' => 'début de l\'activitée jour/mois/année *',
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])

            ->add('endDate', DateType::class, [
                'label' => 'fin de l\'activitée jour/mois/année *',
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('pictur', FileType::class, [
                'label' => 'Sélectionner une photo *',
                'attr' => array(
                    'class' => 'custom-file-input'
                ),
                'constraints' => [
                    new File([
                        // Taille maximum de 1Mo
                        'maxSize' => '1M',

                        // jpg et png uniquement
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],

                        // Message d'erreur en cas de fichier au type non autorisé
                        'mimeTypesMessage' => 'L\'image doit être de type jpg ou png',

                        // Message en cas de fichier trop gros
                        'maxSizeMessage' => 'Fichier trop volumineux ({{ size }} {{ suffix }}). La taille maximum autorisée est {{ limit }}{{ suffix }}',
                    ]),
                    new NotBlank([
                        // Message en cas de formulaire envoyé sans fichier
                        'message' => 'Vous devez sélectionner un fichier',
                    ])
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Créer une activité',
                'attr' => [
                    'class' => 'btn btn-outline-primary col-4 offset-4'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
