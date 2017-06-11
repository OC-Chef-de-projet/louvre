<?php

namespace OC\BookingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class VisitorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name',   TextType::class,array(
                    'attr' => array('class' => 'form-control')
                )
            )
        ->add('surname',   TextType::class,array(
                    'attr' => array('class' => 'form-control')
                )
            )
        ->add('reduced',   CheckboxType::class,array(
                    'attr' => array('class' => ''),
                    'required' => false,
                    'label' => 'Tarif réduit'
                )
            )
        ->add('birthday',   DateType::class,array(
                    'attr' => array('class' => 'form-control'),
                    'widget' => 'single_text',
                    'html5' => false,
                    'format' => 'dd/MM/yyyy'
                )
            )
        ->add('country',   CountryType::class,array(
                    'attr' => array('class' => 'form-control'),
                    'placeholder' => '...',
                )
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\BookingBundle\Entity\Visitor'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oc_Bookingbundle_visitor';
    }


}
