<?php

namespace OC\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use OC\BookingBundle\Form\VisitorType;

class VisitorsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nbticket',   TextType::class,array(
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('visitors', CollectionType::class, array(
                'entry_type' => VisitorType::class
        )
        ->add('save', SubmitType::class,array(
                    'attr' => array('class' => 'btn btn-primary btn-block btn-lg'),
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    /*
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\TicketsBundle\Entity\Visitor'
        ));
    }
    */
    /**
     * {@inheritdoc}
     */
    /*
    public function getBlockPrefix()
    {
        return 'oc_ticketsbundle_visitor';
    }
    */
}
