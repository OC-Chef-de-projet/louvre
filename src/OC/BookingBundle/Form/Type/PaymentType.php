<?php

namespace OC\BookingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PaymentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('email', EmailType::class, [
                        'label'              => 'email_for_tickets',
                        'attr'               => ['class' => 'form-control'],
                        'translation_domain' => 'messages',
                    ]
                )
                ->add('name', TextType::class, [
                        'label' => 'name',
                        'attr'  => [
                            'class'       => 'form-control',
                            'placeholder' => 'name',
                        ],
                        'translation_domain' => 'messages',
                    ]
                )
                ->add('cardno', TextType::class, [
                        'label' => 'credit_card_no',
                        'attr'  => [
                            'class'       => 'form-control',
                            'placeholder' => 'credit_card_no',
                            'data-stripe' => 'number',
                        ],
                        'translation_domain' => 'messages',
                    ]
                )
                ->add('expmonth', ChoiceType::class, [
                        'label' => 'expiration_date',
                        'attr'  => [
                            'class'       => 'form-control',
                            'data-stripe' => 'exp-month',
                        ],
                        'choices'  => [
                            'month_1'  => '01',
                            'month_2'  => '02',
                            'month_3'  => '03',
                            'month_4'  => '04',
                            'month_5'  => '05',
                            'month_6'  => '06',
                            'month_7'  => '07',
                            'month_8'  => '08',
                            'month_9'  => '09',
                            'month_10' => '10',
                            'month_11' => '11',
                            'month_12' => '12',
                        ],
                        'choice_translation_domain' => 'messages',
                    ]
                )
                ->add('expyear', ChoiceType::class, [
                        'label' => ' ',
                        'attr'  => [
                            'class'       => 'form-control',
                            'data-stripe' => 'exp-year',
                        ],
                        'choices'  => array_combine(
                            range(date('Y'), 2025),
                            range(date('Y'), 2025)
                        ),
                    ]
                )
                ->add('cvv', TextType::class, [
                        'label' => 'security_code',
                        'attr'  => [
                            'class'       => 'form-control',
                            'placeholder' => 'security_code',
                            'data-stripe' => 'cvc',
                        ],
                        'translation_domain' => 'messages',
                    ]
                )
                ->add('token', HiddenType::class, [
                        'data' => '',
                    ]
                )
                ->add('save', SubmitType::class, [
                        'label'              => 'label_payment',
                        'attr'               => ['class' => 'btn btn-primary btn-block btn-lg'],
                        'translation_domain' => 'messages',
                    ]
                );
    }
}
