<?php

namespace OC\TicketsBundle\Form;
use OC\TicketsBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visit', TextType::class,array(
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('duration', ChoiceType::class,array(
                    'label' => '',
                    'choices' => array(
                        'Journée' => Ticket::DAY,
                        'demi-journée' => Ticket::HALFDAY
                    ),
                    'multiple' => false,
                    'expanded' => true
                )
            )
            ->add('nbticket',   TextType::class,array(
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('save', SubmitType::class,array(
                    'attr' => array('class' => 'btn btn-primary btn-block btn-lg'),
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
            'data_class' => 'OC\TicketsBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oc_ticketsbundle_ticket';
    }


}
