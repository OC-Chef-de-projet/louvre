<?php

namespace OC\BookingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitorsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('visitors', CollectionType::class, [
                   'entry_type' => VisitorType::class,
                ]
            )
            ->add('save', SubmitType::class, [
                    'attr'               => ['class' => 'btn btn-primary btn-block btn-lg'],
                    'label'              => 'save_label',
                    'translation_domain' => 'messages',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'OC\BookingBundle\Entity\Ticket',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oc_bookingbundle_visitors';
    }
}
