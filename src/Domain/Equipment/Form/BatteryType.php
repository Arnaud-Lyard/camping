<?php

namespace App\Domain\Equipment\Form;

use App\Domain\Equipment\Entity\Battery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;

class BatteryType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add("isActive", CheckboxType::class, [
                "label" => "battery.form.active_label",
                "required" => false,
            ])
            ->add("frequency", IntegerType::class, [
                "label" => "battery.form.frequency_label",
                "constraints" => [
                    new Positive(message: "battery.validation.frequency_positive"),
                    new Range(
                        min: 1,
                        max: 365,
                        notInRangeMessage: "battery.validation.frequency_range",
                    ),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Battery::class,
            "csrf_token_id" => "battery_form",
        ]);
    }
}
