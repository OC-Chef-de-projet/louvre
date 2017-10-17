<?php

namespace OC\BookingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
                    'attr' => ['class' => 'form-control'],
                ]
            )
        ->add('surname', TextType::class, [
                    'attr' => ['class' => 'form-control'],
                ]
            )
        ->add('reduced', CheckboxType::class, [
                    'attr'               => ['class' => ''],
                    'required'           => false,
                    'label'              => 'label_reduced',
                    'translation_domain' => 'messages',
                ]
            )
        ->add('birthday', DateType::class, [
                    'attr'   => ['class' => 'form-control'],
                    'widget' => 'single_text',
                    'html5'  => false,
                    'format' => 'dd/MM/yyyy',
                ]
            )
        ->add('country', CountryType::class, [
                    'attr'               => ['class' => 'form-control'],
                    'translation_domain' => 'messages',
                    'placeholder'        => 'to_be_continued',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'OC\BookingBundle\Entity\Visitor',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oc_Bookingbundle_visitor';
    }
}
