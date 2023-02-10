<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Security;

class CommentType extends AbstractType
{

    public function __construct(private Security $security)
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('content', TextareaType::class, [
                'attr' => [
                    'label' => "Votre commentaire"
                ]
            ])
            ->add('publier', SubmitType::class, [
                'attr' => [
                    'class' => 'cursor-pointer p-4 bg-gray-100 rounded mt-4',
                    'disabled' => $user ? false : true,
                    'title' => $user ? "Publier mon commentaire" : "Connectez-vous pour publier"
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'attr' => array(
                'class' => 'w-full'
            )
        ]);
    }
}
