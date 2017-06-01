<?php

namespace OC\BookingBundle\Form\Type;
use OC\BookingBundle\Entity\Ticket;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PaymentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('email', EmailType::class,array(
                        'label' => 'Votre adresse email où envoyer les billets',
                        'attr' => array('class' => 'form-control')
                    )
                )
                ->add('name', TextType::class,array(
                        'label' => ' ',
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Nom'
                        )
                    )
                )
                ->add('cardno', TextType::class,array(
                        'label' => ' ',
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'N° de carte',
                            'data-stripe' => 'number'
                        )
                    )
                )
                ->add('expmonth', ChoiceType::class,array(
                        'label' => ' ',
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Date expiration mois',
                            'data-stripe' => 'exp-month'
                        ),
                        'choices'  => array_combine(
                            range(1, 12), 
                            range(1, 12)
                        )
                    )
                )
                ->add('expyear', ChoiceType::class,array(
                        'label' => ' ',
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Date expiration année',
                            'data-stripe' => 'exp-year'
                        ),
                        'choices'  => array_combine(
                            range(date('Y'), 2025),
                            range(date('Y'), 2025)
                        )
                    )
                )
                ->add('cvv', TextType::class,array(
                        'label' => ' ',
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Code de sécurité',
                            'data-stripe' => 'cvc'
                        )
                    )
                )
                ->add('token', HiddenType::class, array(
                        'data' => ''
                    )
                )
                ->add('save', SubmitType::class,array(
                        'label' => 'Paiement',
                        'attr' => array('class' => 'btn btn-primary btn-block btn-lg'),
                    )
                )
            ;
    }
}
