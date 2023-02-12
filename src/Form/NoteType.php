<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Security\Core\Security;

class NoteType extends AbstractType
{
    public function __construct(private Security $security)
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('note', RangeType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 5
                ],
            ])
            ->add('noter', SubmitType::class, [
                'attr' => [
                    'class' => 'cursor-pointer p-4 bg-gray-100 rounded mt-4',
                    'disabled' => $user ? false : true,
                    'title' => $user ? "Publier ma note" : "Connectez-vous pour noter"
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
