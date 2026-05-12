<?php
namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr'  => ['placeholder' => 'Titre du livre'],
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur',
                'attr'  => ['placeholder' => 'Nom de l\'auteur'],
            ])
            ->add('isbn', TextType::class, [
                'label'    => 'ISBN',
                'required' => false,
                'attr'     => ['placeholder' => '978-...'],
            ])
            ->add('publishedYear', IntegerType::class, [
                'label'    => 'Année de publication',
                'required' => false,
                'attr'     => ['placeholder' => '2024'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['submit_label'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'   => Book::class,
            'submit_label' => 'Enregistrer',
        ]);
    }
}