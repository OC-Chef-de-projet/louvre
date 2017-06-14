<?php

namespace OC\BookingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
                        'label' => 'email_for_tickets',
                        'attr' => array('class' => 'form-control'),
                        'translation_domain' => 'messages'
                    )
                )
                ->add('name', TextType::class,array(
                        'label' => 'name',
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'name'
                        ),
                        'translation_domain' => 'messages'
                    )
                )
                ->add('cardno', TextType::class,array(
                        'label' => 'credit_card_no',
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'credit_card_no',
                            'data-stripe' => 'number'
                        ),
                        'translation_domain' => 'messages'
                    )
                )
                ->add('expmonth', ChoiceType::class,array(
                        'label' => 'expiration_date',
                        'attr' => array(
                            'class' => 'form-control',
                            'data-stripe' => 'exp-month'
                        ),
                        'choices'  => array(
                            'month_1' => '01',
                            'month_2' => '02',
                            'month_3' => '03',
                            'month_4' => '04',
                            'month_5' => '05',
                            'month_6' => '06',
                            'month_7' => '07',
                            'month_8' => '08',
                            'month_9' => '09',
                            'month_10' => '10',
                            'month_11' => '11',
                            'month_12' => '12',
                        ),
                        'choice_translation_domain' => 'messages',
                    )
                )
                ->add('expyear', ChoiceType::class,array(
                        'label' => ' ',
                        'attr' => array(
                            'class' => 'form-control',
                            'data-stripe' => 'exp-year'
                        ),
                        'choices'  => array_combine(
                            range(date('Y'), 2025),
                            range(date('Y'), 2025)
                        )
                    )
                )
                ->add('cvv', TextType::class,array(
                        'label' => 'security_code',
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'security_code',
                            'data-stripe' => 'cvc'
                        ),
                        'translation_domain' => 'messages'
                    )
                )
                ->add('token', HiddenType::class, array(
                        'data' => ''
                    )
                )
                ->add('save', SubmitType::class,array(
                        'label' => 'label_payment',
                        'attr' => array('class' => 'btn btn-primary btn-block btn-lg'),
                        'translation_domain' => 'messages'
                    )
                )
            ;
    }
}
